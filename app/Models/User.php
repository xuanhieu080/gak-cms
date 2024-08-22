<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (self $record) {
            foreach ($record->mediaFiles()->get() as $entry) {
                $entry->delete();
            }
        });
    }

    /**
     * Returns the user avatar
     * @return HasOne|MediaFile
     */
    public function avatar()
    {
        return $this->hasOne(MediaFile::class, 'id', 'avatar_id');
    }

    /**
     * Returns the user files
     * @return HasMany
     */
    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'user_id', 'id');
    }

    /**
     * Returns the avatar url attribute
     * @return string|null
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $src = $this->getAttribute('avatar_id');
        if (is_null($src)) {
            return asset('assets/images/user-default.png');
        }
        if (!empty($this->avatar)) {
            return asset('storage/'.$this->avatar->path);
        }
        return null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getMediaFolderName()
    {
        return 'users';
    }
}
