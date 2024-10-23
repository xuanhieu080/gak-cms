<?php

namespace App\V1\CMS\Resources\Materials;

use App\V1\CMS\Resources\Warehouses\WarehouseShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class MaterialResource extends JsonResource
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
            "id"                  => $this->id,
            "code"                => $this->code,
            "name"                => $this->name,
            "material_warehouses" => MaterialWarehouseResource::collection($this->materialWarehouses)
        ];
    }
}
