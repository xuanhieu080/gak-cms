<?php

namespace App\V1\CMS\Resources\Products\Variants;

use App\V1\CMS\Resources\AttributeShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class VariantDetailResource extends JsonResource
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
            "variant_id"      => $this->variant_id,
            "attribute"       => new AttributeShortResource($this->attribute),
            "attribute_group" => new AttributeShortResource($this->attributeGroup),
        ];
    }
}
