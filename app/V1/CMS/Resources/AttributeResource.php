<?php

namespace App\V1\CMS\Resources;

use App\Models\AttributeGroup;
use App\Utilities\Data;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"       => $this->id,
            "name"     => $this->name,
            "group"    => AttributeGroup::collection($this->group),
            "group_id" => $this->group_id,
        ];
    }
}
