<?php

namespace App\V1\CMS\Resources\Products\Variants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class VariantResource extends JsonResource
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
            "id"                 => $this->id,
            "price"              => $this->price,
            "price_sale"         => $this->price_sale,
            "sku"                => $this->sku,
            "image"              => $this->getFirstMediaUrl(),
            "details"            => VariantDetailResource::collection($this->details),
            "variant_warehouses" => VariantWarehouseResource::collection($this->variantWarehouses),
            'created_by_name'    => object_get($this, 'createBy.name'),
            'updated_by_name'    => object_get($this, 'updateBy.name'),
        ];
    }
}
