<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;

class CheckBearerToken
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
        // dd($request->bearerToken());

        if (!$request->bearerToken()) {
            return response()->json(['message' => 'no bearerToken'], 401);
        }

        $organization = Organization::where('code', $request->header('x-api-key'))->first();

        // selon l'organization, aller instrospecter le token
        switch ($organization->code) {
            case 'decathlon':
                // code
                break;
            case 'boulanger':
                // code
                break;
            default:
                // code...
                break;
        }

        return $next($request);
    }
}
