<?php

namespace App\V1\CMS\Resources\Products;

use App\V1\CMS\Resources\CategoryShortResource;
use App\V1\CMS\Resources\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class ProductShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"              => $this->id,
            "name"            => $this->name,
            "qty"             => $this->qty,
            "price"           => $this->price,
            "price_sale"      => $this->price_sale,
            "is_active"       => $this->is_active,
            "sku"             => $this->sku,
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
        ];
    }
}
