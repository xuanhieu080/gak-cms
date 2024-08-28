<?php

namespace App\V1\CMS\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'title'       => $this->title,
            'group_id'    => $this->group_id,
            'description' => $this->description,
        ];
    }
}
