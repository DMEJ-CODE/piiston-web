<?php

namespace App\Http\Resources\Identity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_photo' => $this->profile_photo ? (str_starts_with($this->profile_photo, 'http') ? $this->profile_photo : asset($this->profile_photo)) : null,
            'country_id' => $this->country_id,
            'currency' => [
                'code' => $this->country->currency->code ?? 'XAF',
                'symbol' => $this->country->currency->symbol ?? 'FCFA',
            ],
            'roles' => $this->roles->pluck('name'),
            'presence' => $this->presence ? [
                'status' => $this->presence->status,
                'last_seen' => $this->presence->last_active_at,
            ] : null,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
