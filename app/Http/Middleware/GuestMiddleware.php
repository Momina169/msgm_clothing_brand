<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->session()->has('guest_id')){
            $guest_id = 'guest_' . Str::uuid();

            //save to session
            $request->session()->put('guest_id', $guest_id);

            //store guest info in database
            Guest::create([
                'guest_id' => $guest_id,
            ]);
        }  
        return $next($request);
    }
}
