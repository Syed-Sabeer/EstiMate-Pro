<?php

namespace App\Http\Middleware;

use App\Models\UserPlan;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $activePlan = UserPlan::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', Carbon::today());
            })
            ->orderByDesc('start_date')
            ->first();

        if (!$activePlan) {
            return response()->json([
                'message' => 'Access denied. No active plan or plan expired.',
                'plan_status' => 'expired'
            ], 403);
        }

        return $next($request);
    }
}
