<?php

namespace App\Http\Middleware;

use App\Models\Answer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAnswer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $answer = Answer::with("quiz")->whereToken($request->token)->firstOrFail();
        if ($answer->status) {
            return redirect()->route('answer', ['token' => $answer->token]);
        }
        return $next($request);
    }
}
