<?php

namespace App\V1\CMS\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionGroupResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'title'       => $this->name,
            'permissions' => PermissionResource::collection($this->permissions),
            'description' => $this->description,
        ];
    }
}
