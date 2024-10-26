<?php

namespace App\V1\CMS\Resources;

use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class CategoryResource extends JsonResource
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
            "id"          => $this->id,
            "name"        => $this->name,
            "image"       => $this->getFirstMediaUrl(),
            "parent_id"   => $this->parent_id,
            "is_active"   => $this->is_active,
            "description" => $this->description,
            "parent"      => new CategoryShortResource($this->parent),
        ];
    }
}
