<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends BaseModel implements HasMedia
{
    use HasFactory, NodeTrait, InteractsWithMedia;


    protected $fillable = [
        'name',
        'description',
        'is_active',
        '_lft',
        '_rgt',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
      'is_active' => 'boolean',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getMediaFolderName()
    {
        return 'categories';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
