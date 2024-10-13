<?php

namespace App\V1\CMS\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class UnitResource extends JsonResource
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
            "name"            => $this->name,
            "code"            => $this->code,
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
        ];
    }
}
