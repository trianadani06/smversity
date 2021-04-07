<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Adminonly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check())
        {
            return redirect('login')->with('warning','Kamu harus login terlebih dahulu');
        }
        elseif(Auth::user()->role!="admin") 
        {
            return redirect()->back()->with('warning','kamu tidak memiliki hak akses untuk mengakses halaman ini');
        }
        return $next($request);
    }
}

