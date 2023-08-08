<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Http\Request;
use Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Quiz::all();
        return view('admin.quiz.index')
            ->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Quiz::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'is_published' => 1
        ]);

        return redirect()->back()->with('status', 'Blog Post Form Data Has Been inserted');
    }

    /**
     * Display the specified resource.
     */
    public function addQuestion(string $id, Request $request)
    {
        $quiz = Quiz::whereId($id)->firstOrFail();
        $question = Question::create([
            'name' => $request->name,
            'question_type_id' => $request->type,
            'is_active' => true
        ]);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);
        return redirect()->back()->with('status', 'Blog Post Form Data Has Been inserted');
    }
    public function addOption(string $id, Request $request)
    {
        $question = Question::findOrFail($id);

        QuestionOption::create([
            'question_id' => $question->id,
            'name' => $request->name,
            'is_correct' => $request->is_correct == 1 ? true : false
        ]);
        return redirect()->back();
    }
    public function storeQuestion(string $id, Request $request)
    {
        $correct = 0;
        foreach ($request->question as $key => $value) {
            $question = Question::find($key);
            if (in_array($value, $question->options()->where('is_correct', 1)->pluck('id')->toArray())) {
                $correct++;
            }
        }
        $token = Str::random(16);
        Answer::create([
            "quiz_id" => $id,
            "token" => $token,
            "answers" => $request->question,
            "email" => $request->email,
            "score" => $correct / count($request->question)
        ]);
        return redirect()->route('answer', ['token' => $token]);
    }

    public function removeQuestion(string $id)
    {
        $question = Question::whereId($id)->firstOrFail();
        $question->delete();
        $question->quiz_questions()->delete();
        return redirect()->back();
    }

    public function show(string $id)
    {
        $quiz = Quiz::whereSlug($id)->firstOrFail();
        $types = QuestionType::all();
        return view('admin.quiz.show')->with(["quiz" => $quiz, "types" => $types]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
