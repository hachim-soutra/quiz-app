<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
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
use Illuminate\Support\Facades\File;
use Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $data = Quiz::with('questions')->withCount('questions')->latest()
            ->where('name', 'like', "%{$search}%")
            ->paginate(10);
        return view('admin.quiz.index', compact('data'));
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
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        }
        Quiz::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'image' => $request->hasFile('image') ? $filename : "blank.png",
            'is_published' => 1,
        ]);
        return redirect()->back()->with('status', 'Your quiz has been added');
    }

    public function duplicateQuiz(string $id, Request $request)
    {
        $existedQuiz = Quiz::findOrFail($id);
        $quiz = Quiz::create([
            'name' => $existedQuiz->name,
            'description' => $existedQuiz->description,
            'slug' => Str::slug($existedQuiz->name),
            'image' => $existedQuiz->image
        ]);
        $existedQuiz->questions->each(function ($que) use ($quiz) {
            $question = Question::create([
                'name' => $que->question->name,
                'question_type_id' => $que->question->question_type_id,
                'error' => $que->question->error,
                'is_active' => true
            ]);
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_id' => $question->id,
            ]);

            $que->question->options->each(function ($option) use ($question) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'name' => $option->name,
                    'is_correct' => $option->is_correct,
                    'value' => $option->value,
                ]);
            });
        });
        return redirect()->back()->with('status', 'quiz Has Been duplicated');
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
            'name' => $question_exist->name,
            'question_type_id' => $question_exist->question_type_id,
            'error' => $question_exist->error,
            'is_active' => true
        ]);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);

        $question_exist->options->each(function ($option) use ($question) {
            QuestionOption::create([
                'question_id' => $question->id,
                'name' => $option->name,
                'is_correct' => $option->is_correct,
                'value' => $option->value,
            ]);
        });
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

    public function createAnswer(string $id, Request $request)
    {
        // $validated = $request->validate([
        //     'email' => 'required',
        // ]);

        $token = Str::random(16);
        Answer::create([
            "quiz_id" => $id,
            "token" => $token,
            "answers" => [],
            "email" => $request->email ?? "",
            "score" => 0
        ]);

        $question = Question::whereHas("quiz_questions", function ($q) use ($id) {
            $q->where("quiz_id", $id);
        })->whereNull('deleted_at')->firstOrFail();

        return redirect()->route('questions', ['token' => $token, 'id' => $question->id]);
    }

    public function storeQuestion(int $id, int $question_id, Request $request)
    {

        $answer = Answer::findOrFail($id);
        $questions = $answer->answers;
        $questions[$question_id] = isset($request->question[$question_id]) ? $request->question[$question_id] : $request->question;
        $answer->update([
            "answers" => $questions
        ]);
        $questionL = Question::whereHas("quiz_questions", function ($q) use ($answer) {
            $q->where("quiz_id", $answer->quiz_id);
        })->whereNull('deleted_at')
            ->where("id", '>', $question_id)
            ->first();
        if ($questionL) {
            return redirect()->route('questions', ['token' => $answer->token, 'id' => $questionL->id]);
        } else {
            $arr = array_filter($answer->answers, function ($item) {
                return !$item;
            });
            if (count($arr) > 0) {
                return redirect()->route('questions', ['token' => $answer->token, 'id' => array_keys($arr)[0]]);
            }
            return redirect()->route('answer', ['token' => $answer->token]);
        }
    }

    public function removeQuestion(string $id)
    {
        $question = Question::whereId($id)->firstOrFail();
        $question->delete();
        $question->quiz_questions()->delete();
        return redirect()->back();
    }

    public function removeAllQuestions(string $id)
    {
        $quiz = Quiz::whereId($id)->firstOrFail();
        $quiz->questions()->each(function ($question) {
            $question->question->options()->delete();
            $question->question()->delete();
            $question->delete();
        });

        return redirect()->back()->with('status', 'the questions have been deleted');
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

        if ($request->hasFile('image')) {
            $destination = 'images/' . $quiz->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/', $filename);
        }

        $quiz->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'image' => $request->hasFile('image') ? $filename : $quiz->image,
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
        $request->validate([
            'file' => 'required',
        ]);
        Excel::import(new QuizImport, $request->file);
        return redirect()->back()->with('status', 'Quiz Imported Successfully');
    }

    public function questionsImport(Request $request, $id)
    {
        $request->validate([
            'file' => 'required',
        ]);
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
