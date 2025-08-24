<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardControllerRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $mapping = [
        '/attendance/{work_time_id}' => [
            'web'   => [\App\Http\Controllers\User\AttendanceController::class, 'detail'],
            'admin' => [\App\Http\Controllers\Admin\AttendanceController::class, 'detail'],
        ],
        '/stamp_correction_request/list' => [
            'web'   => [\App\Http\Controllers\User\AttendanceController::class, 'request'],
            'admin' => [\App\Http\Controllers\Admin\AttendanceController::class, 'request'],
        ],
    ];

    public function handle(Request $request, Closure $next)
    {

        $path = '/' . ltrim($request->path(), '/');

        if (Auth::guard('admin')->check()) {
            $role = 'admin';
        } elseif (Auth::guard('web')->check()) {
            $role = 'web';
        } else {
            abort(403, 'Unauthorized');
        }

        if (!isset($this->mapping[$path])) {
            return $next($request);
        }

        [$controller_class, $action] = $this->mapping[$path][$role];

        $controller_instance = app($controller_class);

        return $controller_instance->$action($request, ...array_values($request->route()->parameters()));
    }
}
