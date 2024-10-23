<?php

namespace App\V1\CMS\Resources\Materials;

use App\V1\CMS\Resources\AttributeGroupShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class MaterialShortResource extends JsonResource
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
            "id"       => $this->id,
            "name"     => $this->name,
        ];
    }
}
