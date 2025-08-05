<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseReconnect
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            Log::info('Reconnecting to database');
            DB::reconnect();
        }

        return $next($request);
    }
}
