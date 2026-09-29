<?php

namespace App\Http\Resources\Identity;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
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
            // Mirrors the `languages.code` the API accepts on write, so clients
            // can round-trip the preference without a second lookup.
            'language' => [
                'id' => $this->language_id,
                'code' => $this->preferredLanguage->code ?? 'en',
                'name' => $this->preferredLanguage->name ?? 'English',
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
