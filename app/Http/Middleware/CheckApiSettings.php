<?php

namespace App\Http\Middleware;

use App\Services\VetApiService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckApiSettings
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (VetApiService::authenticateUser(Auth::user()->userSetting->key, Auth::user()->userSetting->url)) {
                return $next($request);
            }
        } catch (\Exception $e) {
        }

        return $request->expectsJson()
            ? abort(403, 'URL & API key do not pass validation. Please, check both corresponding fields')
            : Redirect::route('api-key-reset');
    }
}
