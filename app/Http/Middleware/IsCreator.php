<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect(route('index'))
            ->with('msg-danger', 'Você não tem permissão para acessar essa página!');
        } else {
            if ($request->route('nickname') != Auth::user()->nickname) {
                return redirect(route('index'))
                ->with('msg-danger', 'Você não tem permissão para acessar essa página!');
            }
        }
        return $next($request);
    }
}
