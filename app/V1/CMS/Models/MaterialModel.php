<?php

namespace App\V1\CMS\Models;

use App\Models\Material;

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
            throw new \Exception('Thêm dữ liệu thất bại');
        }
        $model->refresh();

        return $model;
    }
}
