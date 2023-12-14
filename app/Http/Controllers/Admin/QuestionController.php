<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Settings;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\Quiz;
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
        // if (!$pass && $answer->quiz->nbr_questions_sequance && $answer->quiz->quiz_type == 3 && count($answer->answers) % $answer->quiz->nbr_questions_sequance == 0 && count($answer->answers) > 0) {
        //     $break = true;
        // }
        return view('question')->with(["answer" => $answer, "break" => $break, "id" => $id]);
    }

    public function ignore($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $questions = $answer->setQuestion($id, null);
        $answer->questions_json = $questions;
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();
        $question = $answer->getQuestions()->where("sort", $answer->getQuestion($id)["sort"] + 1)->first();
        if ($question) {
            return redirect()->route('questions', ['token' => $token, 'id' => $question["id"]]);
        } else {
            return redirect()->route('answer', ['token' => $answer->token]);
        }
    }

    public function review($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $quizQuestionOld = QuizQuestion::findOrFail($id);
        $quizQuestion = QuizQuestion::where("order", $quizQuestionOld->order + 1)->firstOrFail();
        $answer->update([
            "timer" => $answer->quiz->quiz_time ? $request->timer : null,
        ]);
        return redirect()->route('questions', ['token' => $token, 'id' => $quizQuestion->question_id]);
    }

    public function prev($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $question = $answer->getQuestions()->where("sort", $answer->getQuestion($id)["sort"] - 1)->first();
        $answer->update([
            "timer" => $answer->quiz->quiz_time ? $request->timer : null,
        ]);
        return redirect()->route('questions', ['token' => $token, 'id' => $question["id"]]);
    }

    public function next($token, int $question_id, Request $request)
    {
        $request->validate([
            "question" => "required"
        ]);
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $questions = $answer->setQuestion($question_id, $request->question);
        $answer->questions_json = $questions;
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();
        $question = $answer->getQuestions()->where("sort", $answer->getQuestion($question_id)["sort"] + 1)->first();
        if ($question) {
            return redirect()->route('questions', ['token' => $token, 'id' => $question["id"]]);
        } else {
            return redirect()->route('answer', ['token' => $answer->token]);
        }
    }
}
