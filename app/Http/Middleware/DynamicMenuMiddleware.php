<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DynamicMenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $notification = 0;
        if($notification > 0){
        if (Auth::check()) {
            $userId = Auth::user()->id;

            config([
                'adminlte.menu' => array_merge(config('adminlte.menu'), [
                    [
                        'text' => $notification,
                        'icon' => 'far fa-bell',
                        'topnav_right' => true,
                        'icon_color' => 'yellow',  
                        'classes' => 'dropdown',
                        'url' => '#',
                        'route' => ['user.edit', ['user' => $userId]],
                    ]
                ])
            ]);
        }}

        return $next($request);
    }
}
