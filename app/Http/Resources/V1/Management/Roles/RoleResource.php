<?php

namespace App\Http\Resources\V1\Management\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Role
 */
class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "guard_name" => $this->guard_name,
            "is_mutable" => $this->is_mutable,
            "permissions" => $this->getAllPermissions()->map(function (Permission $permission) {
                return $permission->name;
            })
        ];
    }
}
