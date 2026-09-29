<?php

namespace App\Services\Identity;

use App\Models\Globalization\Language;
use App\Models\Identity\Role;
use App\Models\User;
use App\Repositories\Identity\UserRepositoryInterface;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(User $user, array $data): bool
    {
        if (isset($data['role'])) {
            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->roles()->sync([$role->id => ['assigned_at' => now(), 'status' => 'active']]);
            }
        }

        // The API speaks `languages.code` while the column stores a foreign key,
        // so translate the code before the mass assignment below runs. The
        // unprefixed key is dropped so it can never reach the fillable list.
        if (array_key_exists('language', $data)) {
            $languageId = $this->resolveLanguageId($data['language']);
            unset($data['language']);

            if ($languageId === null) {
                return false;
            }

            $data['language_id'] = $languageId;
        }

        return $this->userRepository->updateProfile($user->id, $data);
    }

    /**
     * Persists the user's preferred language from its canonical code.
     */
    public function updateLanguage(User $user, string $code): bool
    {
        $languageId = $this->resolveLanguageId($code);

        if ($languageId === null) {
            return false;
        }

        return $this->userRepository->updateProfile($user->id, [
            'language_id' => $languageId,
        ]);
    }

    /**
     * Resolves a `languages.code` to its primary key.
     *
     * Returns null for unknown or inactive languages so callers can reject the
     * request instead of silently storing a bad preference.
     */
    protected function resolveLanguageId(?string $code): ?int
    {
        if ($code === null || $code === '') {
            return null;
        }

        $language = Language::query()
            ->where('code', $code)
            ->where('status', true)
            ->first();

        return $language?->id;
    }

    /**
     * @param  array<string, mixed>  $preferences
     */
    public function managePreferences(User $user, array $preferences): void
    {
        $user->preference()->updateOrCreate(['user_id' => $user->id], $preferences);
    }

    public function deleteAccount(User $user): void
    {
        // Add any logic to cleanup related data if not cascading
        $user->delete();
    }
}
