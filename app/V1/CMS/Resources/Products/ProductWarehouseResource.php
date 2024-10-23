<?php

namespace App\V1\CMS\Resources\Products;

use App\V1\CMS\Resources\Warehouses\WarehouseShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class ProductWarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"              => $this->id,
            "product"         => new ProductShortResource($this->product),
            "warehouse"       => new WarehouseShortResource($this->warehouse),
            "qty"             => $this->qty,
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
        ];
    }
}
