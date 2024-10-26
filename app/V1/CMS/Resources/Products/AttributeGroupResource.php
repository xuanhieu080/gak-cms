<?php

namespace App\V1\CMS\Resources\Products;

use App\V1\CMS\Resources\AttributeResource;
use App\V1\CMS\Resources\AttributeShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class AttributeGroupResource extends JsonResource
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
            "name"            => $this->name,
            "attributes"      => AttributeShortResource::collection($this->attributes)
        ];
    }
}
