<?php

namespace App\V1\CMS\Models;

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Variant;
use App\Supports\Support;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class VariantModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Variant();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $model = $this->create($data);

        if (empty($model)) {
            throw new Exception('Thêm dữ liệu thất bại');
        }

        if (!empty($data['image'])) {
            $model->addMedia($data['image'])
                ->usingName($model->sku)
                ->usingFileName($model->sku . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                ->toMediaCollection();
        }
        $model->refresh();

        return $model;
    }

    public function update(array $data, array $with = []): mixed
    {
        $productId = $data[$this->model->getKeyName()];
        $model = $this->model
            ->with($with)
            ->find($productId);

        ProductWarehouse::query()
            ->where('product_id', $productId)
            ->whereNull('variant_id')
            ->delete();
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $model->fill($data);

        if ($model->save()) {
            if (!empty($data['image'])) {
                $model->clearMediaCollection();
                $model->addMedia($data['image'])
                    ->usingName($model)
                    ->usingFileName($model->sku . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
                    ->toMediaCollection();
            }
            return $model;
        }

        return false;
    }

    public function syncWarehouse($productId, $id, array $input = []): void
    {
        $model = $this->model
            ->where('product_id', $productId)
            ->where('id', $id)
            ->first();
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $items = Arr::get($input, 'items', []);

        $items = array_map(function ($item) use ($productId, $id) {
            $item['product_id'] = $productId;
            $item['variant_id'] = $id;
            $item['created_at'] = Support::now();
            $item['updated_at'] = Support::now();
            $item['created_by'] = Auth::id();
            $item['updated_by'] = Auth::id();
            return $item;
        }, $items);

        $warehouseIds = array_column($items, 'warehouse_id');

        ProductWarehouse::query()
            ->where('product_id', $productId)
            ->whereNull('variant_id')
            ->delete();

        ProductWarehouse::query()
            ->where('product_id', $productId)
            ->where('variant_id', $id)
            ->whereNotIn('warehouse_id', $warehouseIds)
            ->delete();

        ProductWarehouse::query()
            ->upsert($items, ['variant_id', 'warehouse_id', 'product_id'],['qty']);
    }

    /**
     * @throws Exception
     */
    public function getWarehouse($productId, $id): Collection|array
    {
        $model = $this->model
            ->where('product_id', $productId)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        return ProductWarehouse::query()
            ->with(['warehouse'])
            ->where('product_id', $productId)
            ->where('variant_id', $id)
            ->get();
    }
}
