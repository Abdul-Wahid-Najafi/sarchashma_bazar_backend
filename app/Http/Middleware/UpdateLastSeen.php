<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinute())) {
                $user->update(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}