<?php

namespace App\V1\CMS\Resources;

use App\Supports\Support;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class ProfileResource extends JsonResource
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
            'id'         => $this->id,
            'email'      => $this->email,
            'name'       => $this->name,
            'avatar'     => $this->getFirstMediaUrl(),
            'is_super'   => $this->is_super,
            'permissions' => PermissionResource::collection($this->getAllPermissions()),
            'created_at' => Support::formatTime($this->created_at),
            'updated_at' => Support::formatTime($this->updated_at),
        ];
    }
}
