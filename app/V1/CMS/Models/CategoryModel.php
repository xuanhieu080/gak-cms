<?php

namespace App\V1\CMS\Models;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Support\Arr;

class CategoryModel extends AbstractModel
{
    public function __construct()
    {
        $model = new Category();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $model = $this->create($data);
        if (empty($model)) {
            throw new \Exception('Thêm dữ liệu thất bại');
        }

        $model->refresh();
        $model->addMedia($data['image'])
            ->usingName($model->name)
            ->usingFileName($model->name . '-' . time() . '.' . $data['image']->getClientOriginalExtension())
            ->toMediaCollection();
        return $model;
    }

    public function update(array $data, array $with = []): mixed
    {
        $model = $this->model->with($with)->find($data[$this->model->getKeyName()]);
        if (empty($model)) {
            throw new \Exception('Dữ liệu không tồn tại',404);
        }

        if (!empty($data['parent_id'])) {
            $node = Category::find($data['parent_id']);
            $bool = $node->isDescendantOf($model);
            if ($bool) {
                throw new \Exception("Không thể chọn danh mục con làm danh mục cha");
            }
        }

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
}
