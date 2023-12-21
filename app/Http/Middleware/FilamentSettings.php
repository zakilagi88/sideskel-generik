<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $color = auth()->user()?->settings['color'] ?? config('filament.theme.colors.primary');


        FilamentColor::register(
            [
                'primary' => $color,
            ]
        );

        return $next($request);
    }
}