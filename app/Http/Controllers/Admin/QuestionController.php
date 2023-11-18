<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Settings;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function sort($id, $type)
    {
        $question = QuizQuestion::findOrFail($id);
        if ($type == "up") {
            $question->order++;
            $question->save();
        } else {
            $question->order--;
            $question->save();
        }
        return redirect()->back()->with('status', 'the order questions have been updated');
    }

    public function show($token, $id, $pass = null)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $question = Question::findOrFail($id);

        $break = false;
        if (!$pass && $answer->quiz->nbr_questions_sequance && $answer->quiz->quiz_type == 3 && count($answer->answers) % $answer->quiz->nbr_questions_sequance == 0 && count($answer->answers) > 0) {
            $break = true;
        }
        $logo = Settings::where("name", "logo")->first();
        return view('question')->with(["answer" => $answer, "question" => $question, "break" => $break, "logo" => $logo]);
    }

    public function next($token, $id)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $questionOld = Question::findOrFail($id);
        $question = Question::where("order", ">", $questionOld)->firstOrFail();
        return view('question')->with(["answer" => $answer, "question" => $question,]);
    }

    public function prev($token, $id)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $quizQuestionOld = QuizQuestion::findOrFail($id);
        dd($quizQuestionOld);
        $quizQuestion = QuizQuestion::where("order", "<", $quizQuestionOld)->firstOrFail();
        return view('question')->with(["answer" => $answer, "question" => $quizQuestion->question]);
    }
}
