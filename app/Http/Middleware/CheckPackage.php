<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckPackage {
    public function handle(Request $request, Closure $next, string $plan): mixed {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!auth()->user()->canAccess($plan)) {
            return redirect()->route('dashboard.' . auth()->user()->subscription_plan)
                ->with('error', 'Bu sayfa ' . ucfirst($plan) . ' paket gerektirir.');
        }
        return $next($request);
    }
}