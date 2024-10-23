<?php

namespace App\V1\CMS\Resources\Warehouses;

use App\Supports\Support;
use App\V1\CMS\Resources\Materials\MaterialShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class WarehouseResource extends JsonResource
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
            "address"         => $this->address,
            "phone"           => $this->phone,
            "email"           => $this->email,
            "manager_id"      => $this->manager_id,
            "manager"         => $this->manager,
            "materials"       => MaterialShortResource::collection($this->materials),
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
            'created_at'      => Support::formatTime($this->created_at),
            'updated_at'      => Support::formatTime($this->updated_at),
        ];
    }
}
