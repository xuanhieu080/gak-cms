<?php

namespace App\V1\CMS\Models;

use App\Models\AttributeGroup;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductWarehouse;
use App\Supports\Support;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Product();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        $model = $this->create($data);

        if (empty($model)) {
            throw new Exception('Thêm dữ liệu thất bại');
        }


        if (!empty($data['image'])) {
            $model->addMedia($data['image'])
                ->usingName($model->name)
                ->usingFileName($model->name . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                ->toMediaCollection();
        }
        $model->refresh(['details', 'details.attribute', 'details.attributeGroup']);

        return $model;
    }

    public function update(array $data, array $with = []): mixed
    {
        $model = $this->model->with($with)->find($data[$this->model->getKeyName()]);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $data['is_active'] = filter_var(Arr::get($data, 'is_active', $model->is_active), FILTER_VALIDATE_BOOLEAN);
        $model->fill($data);


        if ($model->save()) {
            if (!empty($data['image'])) {
                $model->clearMediaCollection();
                $model->addMedia($data['image'])
                    ->usingName($model)
                    ->usingFileName($model->name . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                    ->toMediaCollection();
            }
            return $model;
        }

        return false;
    }

    public function syncAttribute($id, array $input = []): void
    {
        $model = $this->model->find($id);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $attributeGroups = Arr::get($input, 'groups', []);

        if (empty($attributeGroups)) {
            ProductAttribute::query()
                ->where('product_id', $model->id)
                ->delete();

            ProductWarehouse::query()
                ->where('product_id', $id)
                ->delete();
        } else {
            ProductAttribute::query()
                ->where('product_id', $model->id)
                ->whereNotIn('attribute_group_id', $attributeGroups)
                ->delete();

            $param = [];

            foreach ($attributeGroups as $detail) {
                $param[] = [
                    'attribute_group_id' => $detail,
                    'product_id'         => $model->id,
                    'is_active'          => 1
                ];
            }

            ProductAttribute::query()
                ->upsert($param, ['attribute_group_id', 'product_id']);
        }
    }

    /**
     * @throws Exception
     */
    public function getAttribute($id): Collection|array
    {
        $model = $this->model->find($id);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        return AttributeGroup::query()
            ->whereHas('productAttributes', function ($query) use ($model) {
                $query->where('product_id', $model->id);
            })
            ->get();
    }

    public function syncWarehouse($id, array $input = []): void
    {
        $model = $this->model
            ->with(['variants'])
            ->where('id', $id)
            ->first();
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        if (count($model->variants) > 0) {
            throw new Exception('Không thể thêm sản phẩm có biến thể vào kho hàng', 400);
        }

        $items = Arr::get($input, 'items', []);

        $items = array_map(function ($item) use ($id) {
            $item['product_id'] = $id;
            $item['variant_id'] = null;
            $item['updated_at'] = Support::now();
            $item['created_by'] = Auth::id();
            $item['updated_by'] = Auth::id();
            return $item;
        }, $items);

        $warehouseIds = array_column($items, 'warehouse_id');

        ProductWarehouse::query()
            ->where('product_id', $id)
            ->where(function ($query) use ($warehouseIds) {
                $query->whereNotIn('warehouse_id', $warehouseIds)
                    ->orWhereNotNull('variant_id');
            })
            ->delete();

        // Bước 1: Lấy danh sách các bản ghi đã tồn tại
        $existingRecords = ProductWarehouse::query()
            ->where('product_id', $id)
            ->whereNull('variant_id')
            ->get(['warehouse_id', 'qty']);

        // Tạo một mảng để theo dõi số lượng cập nhật
        $updateQuantities = [];
        $newItems = [];

        // Bước 2: Kiểm tra và phân loại các mục
        foreach ($items as $item) {
            $existingRecord = $existingRecords->firstWhere('warehouse_id', $item['warehouse_id']);

            if ($existingRecord) {
                // Nếu bản ghi đã tồn tại, cộng dồn quantity
                $updateQuantities[$item['warehouse_id']] = $item['qty'];
            } else {
                // Nếu bản ghi chưa tồn tại, thêm mới
                $newItems[] = $item;
            }
        }

        // Bước 3: Thực hiện cập nhật và thêm mới trong một giao dịch
        DB::transaction(function () use ($updateQuantities, $newItems, $id) {
            // Cập nhật quantity cho các bản ghi đã tồn tại bằng một truy vấn duy nhất
            foreach ($updateQuantities as $warehouseId => $qty) {
                ProductWarehouse::where('product_id', $id)
                    ->where('warehouse_id', $warehouseId)
                    ->update(['qty' => $qty, 'updated_at' => now()]);
            }

            // Thêm mới các mục nếu có
            if (!empty($newItems)) {
                ProductWarehouse::query()->insert($newItems);
            }
        });
    }

    /**
     * @throws Exception
     */
    public function getWarehouse($id): Collection|array
    {
        $model = $this->model
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        return ProductWarehouse::query()
            ->with(['warehouse', 'product'])
            ->where('product_id', $id)
            ->get();
    }
}
