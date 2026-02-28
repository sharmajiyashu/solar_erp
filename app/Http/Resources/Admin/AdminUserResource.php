<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  parent::toArray($request);

        $roles = $this->roles;

        if ($roles->isNotEmpty()) {
            foreach ($roles as $role) {
                $role_id =  $role->id;
                $role_name = $role->name;
            }
        } else {
            $role_id = '';
            $role_name = '';
        }

        $data['role_name'] = $role_name;
        $data['role_id'] = $role_id;
        return $data;
    }
}
