<?php

namespace App\Http\Middleware;

use App\Models\Establishment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablishmentRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $establishment = $request->route('establishment');

        if (!$establishment instanceof Establishment) {
            abort(404);
        }

        $membership = $request->user()
            ->establishments()
            ->whereKey($establishment->id)
            ->first();

        if (!$membership) {
            abort(403);
        }

        if (!in_array($membership->pivot->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}