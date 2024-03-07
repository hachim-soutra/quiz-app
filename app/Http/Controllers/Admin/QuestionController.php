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
        if ($answer->status) {
            return redirect()->route('answer', ['token' => $answer->token]);
        }
        $break = false;
        $question = $answer->getQuestion($id);
        $break_text = Settings::where("name", "Take break text")->first();
        if (
            !$pass && $answer->quiz->nbr_questions_sequance
            && $answer->quiz->quiz_type == 3
            && (($question['sort'] - 1) % $answer->quiz->nbr_questions_sequance) == 0
            && $question['sort'] > 1
        ) {
            if ($answer->getQuestionsReview()->sortBy('sort')->first()) {
                return view('question')->with(["answer" => $answer, "break" => $break, "id" => $answer->getQuestionsReview()->sortBy('sort')->first()["id"], "break_text" => $break_text]);
            }
            $break = true;
        }
        if ($break) {
            $answer->questions_json = $answer->setSkipped($question['sort']);
            $answer->nbr_of_breaks = $question['sort'] / $answer->quiz->nbr_questions_sequance;
            $answer->save();
        }
        return view('question')->with(["answer" => $answer, "break" => $break, "id" => $id, "break_text" => $break_text]);
    }

    public function ignore($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $questions = $answer->setQuestion($id, null);
        $answer->questions_json = $questions;
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();
        return $this->redirectQuestion($answer, $id);
    }

    public function preview($token, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();
        return redirect()->route('questions', ['token' => $answer->token, 'id' => $request->question_id]);
    }

    public function review($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $questions = $answer->setQuestion($id, "review");
        $answer->questions_json = $questions;
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();
        return $this->redirectQuestion($answer, $id);
    }

    public function prev($token, $id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $answer->update([
            "timer" => $answer->quiz->quiz_time ? $request->timer : null,
        ]);
        return $this->redirectQuestion($answer, $id, "prev");
    }

    public function next($token, int $question_id, Request $request)
    {
        $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
        $answer->timer = $answer->quiz->quiz_time ? $request->timer : null;
        $answer->save();

        $request->validate([
            "question" => "required"
        ]);
        $questions = $answer->setQuestion($question_id, $request->question);
        $answer->questions_json = $questions;
        $answer->save();
        return $this->redirectQuestion($answer, $question_id);
    }

    public function redirectQuestion($answer, $id, $type = "next")
    {
        if ($type === "next") {
            $question = $answer->getQuestions()->sortBy('sort')->Where("value", -1)->where("sort", ">=", $answer->getQuestion($id)["sort"] + 1)->first();
        } else {
            $question = $answer->getQuestions()->sortBy('sort')->where("sort", $answer->getQuestion($id)["sort"] - 1)->first();
        }
        if ($question) {
            return redirect()->route('questions', ['token' => $answer->token, 'id' => $question["id"]]);
        } else {
            if ($answer->getQuestionsReview()->first()) {
                return redirect()->route('questions', ['token' => $answer->token, 'id' => $answer->getQuestionsReview()->first()["id"]]);
            }
            $answer->update(["status" => "good"]);
            return redirect()->route('answer', ['token' => $answer->token]);
        }
    }
}
