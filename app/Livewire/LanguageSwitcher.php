<?php

namespace App\Livewire;

use App\Models\Globalization\Language;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Language Switcher')]
class LanguageSwitcher extends Component
{
    public ?string $currentLocale = null;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    public function switchLocale(string $locale): void
    {
        $language = Language::where('code', $locale)->first();

        if (! $language) {
            return;
        }

        session(['locale' => $locale]);

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->preference) {
                $user->preference->update(['language_id' => $language->id]);
            } else {
                $user->preference()->create(['language_id' => $language->id]);
            }
        }

        $this->currentLocale = $locale;
        $this->dispatch('notify', message: $locale === 'fr' ? 'Langue changée en français' : 'Language changed to English');
    }

    public function render()
    {
        $languages = Language::whereIn('code', ['en', 'fr'])->get();

        return view('livewire.language-switcher', compact('languages'));
    }
}
