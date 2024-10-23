<?php

namespace App\V1\CMS\Models;

use App\Models\Material;
use App\Models\MaterialWarehouse;
use App\Supports\Support;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MaterialModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Material();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $model = $this->create($data);
        if (empty($model)) {
            throw new Exception('Thêm dữ liệu thất bại');
        }
        $model->refresh();

        return $model;
    }

    public function syncWarehouse($id, array $input = []): void
    {
        $model = $this->model->find($id);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        $items = Arr::get($input, 'items', []);

        $items = array_map(function ($item) use ($id) {
            $item['material_id'] = $id;
            $item['created_at'] = Support::now();
            $item['updated_at'] = Support::now();
            $item['created_by'] = Auth::id();
            $item['updated_by'] = Auth::id();
            return $item;
        }, $items);

        $warehouseIds = array_column($items, 'warehouse_id');

        MaterialWarehouse::query()
            ->where('material_id', $id)
            ->whereNotIn('warehouse_id', $warehouseIds)
            ->delete();

        MaterialWarehouse::query()
            ->upsert($items, ['warehouse_id', 'material_id'], ['qty', 'created_at', 'updated_by', 'updated_at', 'updated_at']);
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

        return MaterialWarehouse::query()
            ->with(['warehouse'])
            ->where('material_id', $id)
            ->get();
    }
}
