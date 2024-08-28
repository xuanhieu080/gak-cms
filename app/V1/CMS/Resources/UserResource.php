<?php

namespace App\V1\CMS\Resources;

use App\Supports\Support;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
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
            'id'              => $this->id,
            'email'           => $this->email,
            'name'            => $this->name,
            'avatar'          => $this->getFirstMediaUrl(),
            'is_super'        => $this->is_super,
            'created_by_name' => object_get($this, 'createBy.name'),
            'updated_by_name' => object_get($this, 'updateBy.name'),
            'created_at'      => Support::formatTime($this->created_at),
            'updated_at'      => Support::formatTime($this->updated_at),
        ];
    }
}
