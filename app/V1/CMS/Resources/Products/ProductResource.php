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
class ProductResource extends JsonResource
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
            "name"               => $this->name,
            "description"        => $this->description,
            "qty"                => $this->qty,
            "price"              => $this->price,
            "price_sale"         => $this->price_sale,
            "category_id"        => $this->category_id,
            "category"           => new CategoryShortResource($this->category),
            "unit_id"            => $this->unit_id,
            "unit"               => new UnitResource($this->unit),
            "is_active"          => $this->is_active,
            "sku"                => $this->sku,
            "product_warehouses" => ProductWarehouseResource::collection($this->productWarehouses),
            'created_by_name'    => object_get($this, 'createBy.name'),
            'updated_by_name'    => object_get($this, 'updateBy.name'),
        ];
    }
}
