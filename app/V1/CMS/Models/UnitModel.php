<?php

namespace App\V1\CMS\Models;

use App\Models\Unit;
use Illuminate\Support\Arr;

class UnitModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Unit();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        $model = $this->create($data);
        if (empty($model)) {
            throw new \Exception('Thêm dữ liệu thất bại');
        }
        $model->refresh();

        return $model;
    }

    public function update(array $data, array $with = []): mixed
    {
        $model = $this->model->with($with)->find($data[$this->model->getKeyName()]);
        if (empty($model)) {
            throw new \Exception('Dữ liệu không tồn tại', 404);
        }

        $data['is_active'] = filter_var(Arr::get($data, 'is_active', $model->is_active), FILTER_VALIDATE_BOOLEAN);
        $model->fill($data);


        if ($model->save()) {
            return $model;
        }

        return false;
    }
}
