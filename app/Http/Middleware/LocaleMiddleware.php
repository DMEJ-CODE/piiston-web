<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        if ($locale) {
            app()->setLocale($locale);
            $request->attributes->set('locale', $locale);
        }

        return $next($request);
    }

    private function resolveLocale(Request $request): ?string
    {
        if ($locale = session('locale')) {
            return $locale;
        }

        $user = Auth::user();

        if ($user && $user->preference && $user->preference->language) {
            return $user->preference->language->code;
        }

        return 'en';
    }
}
