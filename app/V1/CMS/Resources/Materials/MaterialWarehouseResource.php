<?php

namespace App\V1\CMS\Resources\Materials;

use App\V1\CMS\Resources\Warehouses\WarehouseShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class MaterialWarehouseResource extends JsonResource
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
            "material"        => new MaterialShortResource($this->material),
            "warehouse"       => new WarehouseShortResource($this->warehouse),
            "qty"             => $this->qty,
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
        ];
    }
}
