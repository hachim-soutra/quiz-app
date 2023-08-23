<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\QuestionImport;
use App\Imports\QuizImport;
use App\Models\Answer;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Quiz::with('questions')->get();
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
            'error' => $request->error,
            'is_active' => true
        ]);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);
        return redirect()->back()->with('status', 'Question Has Been inserted');
    }

    public function duplicateQuestion(string $id, string $qst_id, Request $request)
    {
        $quiz = Quiz::whereId($id)->firstOrFail();
        $question_exist = Question::findOrFail($qst_id);
        $question = Question::create([
            'id' => $question_exist->id,
            'name' => $question_exist->name,
            'question_type_id' => $question_exist->type,
            'error' => $question_exist->error,
            'is_active' => true
        ]);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_exist->id,
        ]);
        return redirect()->back()->with('status', 'Question Has Been duplicated');
    }

    public function updateQuestion(string $id, Request $request)
    {
        $question = Question::findOrFail($id);
        $question->update([
            'name' => $request->name,
            'question_type_id' => $request->type,
            'error' => $request->error,
            'is_active' => true
        ]);
        return redirect()->back()->with('status', 'Question Has Been updated');
    }

    public function questionsShow(string $id)
    {
        $question = Question::findOrFail($id);
        return view('admin.quiz.question')->with(["question" => $question]);
    }



    public function storeQuestion(string $id, Request $request)
    {
        // dd($request->all());
        $correct = 0;
        foreach ($request->question as $key => $value) {
            $question = Question::find($key);
            if ($question->question_type->name === 'one answer' && in_array($value[0], $question->options()->where('is_correct', 1)->pluck('id')->toArray())) {
                $correct++;
            }

            if ($question->question_type->name === 'multiple answer' && count(array_diff($value, $question->options()->where('is_correct', 1)->pluck('id')->toArray())) == 0) {
                $correct++;
            }

            if ($question->question_type->name === 'row answers') {
                $correct++;

                foreach ($question->options as $option) {
                    if ($value[$option->id] !== $option->value) {
                        $correct--;
                        break;
                    }
                }
            }
        }
        $token = Str::random(16);
        Answer::create([
            "quiz_id" => $id,
            "token" => $token,
            "answers" => $request->question,
            "email" => $request->email,
            "score" => $correct * 100 / count($request->question)
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
        $quiz = Quiz::whereSlug($id)->firstOrFail();
        $quiz->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->back()->with('status', 'Quiz updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::whereSlug($id)->firstOrFail();
        $quiz->delete();
        return redirect()->back()->with('status', 'Quiz deleted Successfully');
    }

    public function import(Request $request)
    {
        Excel::import(new QuizImport, $request->file);
        return redirect()->back()->with('status', 'Quiz Imported Successfully');
    }

    public function questionsImport(Request $request, $id)
    {
        Excel::import(new QuestionImport($id), $request->file);
        return redirect()->back()->with('status', 'Questions Imported Successfully');
    }


    public function addOption(string $id, Request $request)
    {
        $question = Question::findOrFail($id);
        if ($question->question_type->name === 'one answer' && $request->is_correct == 1) {
            $question->options()->update(['is_correct' => 0]);
        }
        QuestionOption::create([
            'question_id' => $question->id,
            'name' => $request->name,
            'is_correct' => $request->is_correct == 1,
            'value' => $question->question_type->name === 'row answers' ? $request->value : '',
        ]);
        return redirect()->back();
    }

    public function updateOption(string $id, Request $request)
    {
        $option = QuestionOption::findOrFail($id);
        if ($option->question->question_type->name === 'one answer' && $request->is_correct == 1) {
            $option->question->options()->update(['is_correct' => 0]);
        }
        $option->update([
            'name' => $request->name,
            'question_type_id' => $request->type,
            'is_correct' => $request->is_correct == 1,
            'is_active' => true,
            'value' => $option->question->question_type->name === 'row answers' ? $request->value : '',

        ]);
        return redirect()->back()->with('status', 'Answer Has Been updated');
    }

    public function removeOption(string $id)
    {
        $question = QuestionOption::whereId($id)->firstOrFail();
        $question->delete();
        return redirect()->back()->with('status', 'Answer has been deleted');
    }
}
