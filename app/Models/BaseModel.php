<?php

namespace App\Models;

use App\Supports\Support;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{

    public static function boot()
    {
        parent::boot();
        // Write Log
        static::creating(function ($model) {
            $userId = Auth::id();
            $model->created_by = $userId;
            $model->updated_by = $userId;

            $date = Support::now();
            $model->created_at = $date;
            $model->updated_at = $date;
        });

        static::updating(function ($model) {
            $userId = Auth::id();
            $model->created_by = $userId;
            $model->updated_by = $userId;

            $date = Support::now();
            $model->created_at = $date;
            $model->updated_at = $date;
        });

        static::saving(function ($model) {
            $userId = Auth::id();
            $model->created_by = $userId;
            $model->updated_by = $userId;

            $date = Support::now();
            $model->created_at = $date;
            $model->updated_at = $date;
        });

    }

    public function whereCreateBy(&$query): void
    {
        $query->where($this->getTable() . '.created_by', Auth::id());
    }

    public function whereUpdateBy(&$query): void
    {
        $query->where($this->getTable() . '.updated_by', Auth::id());
    }

    public function createBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updateBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
