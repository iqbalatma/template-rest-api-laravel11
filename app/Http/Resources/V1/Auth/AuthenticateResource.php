<?php

namespace App\Http\Resources\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthenticateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this["user"];
        return [
            "id" => $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "full_name" => $user->full_name,
            "email" => $user->email,
            "phone_number" => $user->phone_number,
            "address" => $user->profile?->address,
            "gender" => $user->profile?->gender,
            "avatar" => $user->profile?->avatar,
            "birth_place" => $user->profile?->birth_place,
            "birth_date" => $user->profile?->birth_date,
            "created_at" => $user->created_at,
            "token" => $this["token"]
        ];
    }
}
