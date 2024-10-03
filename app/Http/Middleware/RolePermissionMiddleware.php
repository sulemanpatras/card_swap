<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        if (in_array('admin', $roles)) {

            return $next($request);
        } else {

            $routeName = $request->route()->getName();
            if ($routeName === null || Str::startsWith($routeName, 'generated::')) {

                return $next($request);
            }


            foreach ($roles as $roleName) {
                $role = Role::where('name', $roleName)->first();

                if ($role && $role->hasPermissionTo($routeName)) {

                    return $next($request);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }
        }
    }
}
