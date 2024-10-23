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
        $model->refresh();

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
            ->with(['attributes'])
            ->where('id', $id)
            ->first();
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        if (!empty($model->attributes)) {
            throw new Exception('Không thể thêm sản phẩm có biến thể vào kho hàng', 400);
        }

        $items = Arr::get($input, 'items', []);

        $items = array_map(function ($item) use ($id) {
            $item['product_id'] = $id;
            $item['created_at'] = Support::now();
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
                    ->whereNotNull('variants');
            })
            ->delete();

        ProductWarehouse::query()
            ->upsert($items, ['variant_id', 'warehouse_id', 'product_id'], ['qty']);
    }

    /**
     * @throws Exception
     */
    public function getWarehouse($id): Collection|array
    {
        $model = $this->model
            ->where('product_id', $id)
            ->first();

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        return ProductWarehouse::query()
            ->with(['warehouse'])
            ->where('product_id', $id)
            ->get();
    }
}
