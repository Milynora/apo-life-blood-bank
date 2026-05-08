<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Changed isApproved() to isActive()
        if ($user && !$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your account is not active. Status: ' . $user->status->value);
        }

        return $next($request);
    }
}

