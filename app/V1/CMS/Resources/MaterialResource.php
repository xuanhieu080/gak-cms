<?php

namespace App\V1\CMS\Resources;

use App\Models\AttributeGroup;
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
            "id"           => $this->id,
            "code"         => $this->code,
            "name"         => $this->name,
            "warehouse"    => new WarehouseShortResource($this->warehouse),
            "warehouse_id" => $this->warehouse_id
        ];
    }
}
