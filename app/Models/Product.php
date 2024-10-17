<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
      'id',
      'name',
      'description',
      'price',
      'price_sale',
      'discount',
      'category_id',
      'qty',
      'qty_sold',
      'is_active',
      'sku',
      'unit_id'
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
        return 'products';
    }

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function unit() {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
}
