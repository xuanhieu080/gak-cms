<?php

namespace App\V1\CMS\Models;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserModel extends AbstractModel
{
    public function __construct()
    {
        $model = new User();
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $model = $this->create($data);
        if (empty($model)) {
            throw new \Exception('Thêm dữ liệu thất bại');
        }
        if (!empty($data['image'])) {
            $image = $data['image'];
            $mediaItem = $model->getMedia('default')->first();
            if (!empty($mediaItem)) {
                $mediaItem->delete();
            }
            $model->addMedia($image)
                ->usingName($model->name)
                ->usingFileName(Str::slug($model->name) . time() . Str::random(8) . '.' . $image->getClientOriginalExtension())
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

        if (!empty($data['password'])) {
            $model->password = Hash::make($data['password']);
        }
        $model->fill($data);

        if ($model->save()) {
            if (!empty($data['image'])) {
                $image = $data['image'];
                $mediaItem = $model->getMedia('default')->first();
                if (!empty($mediaItem)) {
                    $mediaItem->delete();
                }

                $model->addMedia($image)
                    ->usingName($model->name)
                    ->usingFileName(Str::slug($model->name) . time() . Str::random(8) . '.' . $image->getClientOriginalExtension())
                    ->toMediaCollection();
            }
            return $model;
        }

        return false;
    }

    public function updateAvatar(array $data, array $with = []): mixed
    {
        $model = $this->model->with($with)->find($data[$this->model->getKeyName()]);
        if (empty($model)) {
            throw new Exception('Dữ liệu không tồn tại', 404);
        }

        if (!empty($data['image'])) {
            $image = $data['image'];
            $mediaItem = $model->getMedia('default')->first();
            if (!empty($mediaItem)) {
                $mediaItem->delete();
            }
            $model->addMedia($image)
                ->usingName($model->name)
                ->usingFileName(Str::slug($model->name) . time() . Str::random(8) . '.' . $image->getClientOriginalExtension())
                ->toMediaCollection();
        }

        return $model;
    }

    public function deleteModel(int $id): bool
    {
        $model = $this->getById($id);
        if (empty($model)) {
            throw new \Exception('Dữ liệu không tồn tại');
        }

        return $model->delete();
    }


    public function syncPermissions(int $id, array $data = []): array|Builder|Collection|Model
    {
        $model = $this->getById($id);
        if (empty($model)) {
            throw new \Exception('Dữ liệu không tồn tại');
        }

        $permissions = Arr::get($data, 'permissions', []);
        $model->syncPermissions($permissions);
        return $model;
    }
}
