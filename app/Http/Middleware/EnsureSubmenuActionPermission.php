<?php

namespace App\Http\Middleware;

use App\Models\admin\Submenu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubmenuActionPermission
{
    public function handle(Request $request, Closure $next, string $action = 'view', ?string $submenuRoute = null): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (! method_exists($user, 'canSubmenuAction')) {
            abort(403);
        }

        if ($this->canBypass($user)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $routeToCheck = $submenuRoute ?: $routeName;

        if (! $routeToCheck || ! $user->canSubmenuAction($action, $routeToCheck)) {
            abort(403, 'No tiene permiso para ejecutar esta accion.');
        }

        return $next($request);
    }

    protected function canBypass($user): bool
    {
        $admins = array_filter(array_map('trim', explode(',', (string) env('ADMINS', ''))));

        return (bool) ($user->email && in_array($user->email, $admins, true));
    }
}
