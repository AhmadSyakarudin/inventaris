<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request); 
        }

        if ($userRole === 'admin') {
            return redirect('/admin/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        if ($userRole === 'operator') {
            return redirect('/staff/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return redirect('/')->with('error', 'Role tidak dikenali atau akses tidak diperbolehkan.');
    }
}