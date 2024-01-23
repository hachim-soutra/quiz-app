<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\QuizTheme;
use App\Models\User;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('client.home');
    }

    public function settings()
    {
        return view('client.account');
    }

    public function quizzes()
    {
        $quizzes = Quiz::OrderBy('payement_type')->get();
        return view('client.quizzes',['quizzes' => $quizzes]);
    }

    public function updateAccount(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'email',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'updated_at' => now()
        ]);
        return redirect()->back()->with('status', 'Profile Updated');
    }

    public function answers()
    {
        $answer = Answer::with("quiz")->where('email',Auth::user()->email)->get();
        return view('client.answers')->with([ "answers"=> $answer]);
    }

    public function destroy(Request $request)
    {
        $answer = Answer::whereIn('id', $request->item)->get();
        foreach($answer as $item) {
            $item->delete();
        };
        return response()->json(['message'=>'answer(s) deleted Successfully'],200);
    }
}
