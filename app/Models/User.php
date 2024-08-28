<?php

namespace App\Models;

use App\Supports\Support;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];


    const path = 'users';

    protected $fillable = [
      'name',
      'email',
      'is_super',
      'password',
      'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot(): void
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getMediaFolderName(): string
    {
        return 'users';
    }
}
