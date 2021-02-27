<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;

class CheckXApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->header('x-api_key'));

        if (!$request->header('x-api-key')) {
            return response()->json(['message' => 'no x-api_key'], 403);
        }

        $organization = Organization::where('code', $request->header('x-api-key'))->first();

        if (!$organization) {
            return response()->json(['message' => 'unknown x-api_key'], 403);
        }

        return $next($request);
    }
}
