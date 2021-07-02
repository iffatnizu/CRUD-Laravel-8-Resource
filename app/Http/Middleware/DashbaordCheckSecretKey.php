<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class DashbaordCheckSecretKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->bearerToken();
        if ($key != env('API_SECRET_KEY')) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
        return $next($request);
    }
}
