<?php

namespace App\V1\CMS\Models;

use App\Models\User;
use App\V1\Models\AbstractModel;

class UserModel extends AbstractModel
{
    public function __construct()
    {
        $model = new User();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        return $this->create($data);
    }

    public function updateModel(array $data)
    {
        return $this->update($data);
    }

    public function deleteModel($id)
    {
        $item = $this->model->find($id);
        if (empty($item)) {
            throw new \Exception("Không tìm thấy dữ liệu");
        }
        return $item->delete();
    }
}
