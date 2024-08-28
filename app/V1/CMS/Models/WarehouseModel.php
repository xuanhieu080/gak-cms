<?php

namespace App\V1\CMS\Models;

use App\Models\Warehouse;

class WarehouseModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Warehouse();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $model = $this->create($data);
        if (empty($model)) {
            throw new \Exception('Thêm dữ liệu thất bại');
        }
        $model->refresh();

        return $model;
    }
}
