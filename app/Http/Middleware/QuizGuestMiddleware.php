<?php

namespace App\Http\Middleware;

use App\Enum\PayementTypeEnum;
use App\Models\User;
use Closure;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $quiz = Quiz::where('slug',$request->route()->parameter('slug'))->firstOrFail();

        if($quiz->payement_type == PayementTypeEnum::PAYED->value)
        {
            // case 1 : not auth redirect to login
            if(!auth()->check() || auth()->user()->user_type != User::CLIENT_TYPE) return redirect()->route('login');
            // case 2 : quiz not purchased redirect to payment page
            if($quiz->isPurchasedBy(auth()->user()->id) == 0) return redirect()->route('checkout', ['price_token' => $quiz->price_token, 'quiz_id' => $quiz->id]);
        }
        return $next($request);
    }

}
