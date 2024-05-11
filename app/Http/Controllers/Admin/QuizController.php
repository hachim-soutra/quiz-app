<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PayementTypeEnum;
use App\Http\Controllers\Controller;
use App\Imports\QuestionImport;
use App\Imports\QuizImport;
use App\Models\Answer;
use Carbon\Carbon;
use App\Models\QuizTheme;
use App\Models\Settings;
use App\Models\Order;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use App\Models\QuestionsCategorization;
use App\Services\StripeService;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Laravel\Cashier\Cashier;
use Str;

class QuizController extends Controller
{
    public StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $qq = $request->q;
        $folders = QuizTheme::whereHas('quizzes')->search($qq)->with('quizzes', function ($q) use ($qq) {
            $q->where('name', 'LIKE', "%$qq%");
        })
            ->orWhereHas('quizzes', function ($q) use ($qq) {
                $q->where('name', 'LIKE', "%$qq%");
            })->whereHas("quizzes")
            ->withCount('quizzes')
            ->orderBy('label')->paginate(10);
        return view('admin.quiz.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $folders = QuizTheme::all();
        return view('admin.quiz.create', compact('folders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quiz_type' => 'required',
            'payement_type' => 'required',
            'price' => 'required_if:payement_type,==,paid,numeric'
        ]);

        if ($request->quiz_type != 1) {
            $request->validate([
                'quiz_time' => 'date_format:H:i',
                'quiz_time_remind' => 'date_format:H:i,before:quiz_time',
                'nbr_questions_sequance' => 'required_if:quiz_type,==,3',
                'break_time' => 'required_if:quiz_type,==,3'
            ]);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        }
        $price_token = null;
        $productToken = null;
        if ($request->payement_type == PayementTypeEnum::PAYED->value) {
            $product = $this->stripeService->createProduct($request->name, $request->price);
            $price_token = $product->default_price;
            $productToken = $product->id;
        }

        Quiz::create([
            'quiz_type' => $request->quiz_type,
            'name' => $request->name,
            'payement_type' => $request->payement_type,
            'price' => $request->price,
            'description' => $request->description,
            'folder_id' => $request->folder,
            'quiz_time' => $request->quiz_time,
            'quiz_time_remind' => $request->quiz_time_remind,
            'nbr_questions_sequance' => $request->nbr_questions_sequance,
            'break_time' => $request->break_time,
            'slug' => Str::slug($request->name),
            'image' => $request->hasFile('image') ? $filename : "blank.png",
            'price_token' => $price_token,
            'product_token' => $productToken
            // 'is_published' => 1,
        ]);
        return redirect()->route('quiz.index')->withInput()->with('status', 'Your quiz has been added');
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
    public function removeQuestionImage(string $id)
    {
        $question = Question::whereId($id)->firstOrFail();
        $question->update([
            'image' => null
        ]);
        return redirect()->back()->with('status', 'Question image Has Been deleted');
    }

    public function addQuestion(string $id, Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/question', $filename);
        }
        $quiz = Quiz::whereId($id)->firstOrFail();
        $question = Question::create([
            'name' => $request->name,
            'question_type_id' => $request->type,
            'error' => $request->error,
            'image' => $request->hasFile('image') ? $filename : null,
            'categorie_id' => $request->categorie,
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
            'image' => $question_exist->image,
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
        if ($request->hasFile('image')) {
            $destination = 'images/' . $question->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/question', $filename);
        }
        $question->update([
            'name' => $request->name,
            'question_type_id' => $request->type,
            'error' => $request->error,
            'categorie_id' => $request->categorie,
            'is_active' => true,
            'image' => $request->hasFile('image') ? $filename : $question->image,
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
        $target = Settings::where('name', 'LIKE', "answer target")->first();
        $token = Str::random(16);
        $quiz = Quiz::findOrFail($id);
        $sort = 0;
        $question_json = $quiz->questions()->whereHas('question')->with('question')->get()->sortBy('question.name')->map(function ($question) use (&$sort, $quiz) {
            $sort++;
            return [
                'id' => $question->question?->id,
                'name' => $question->question?->name,
                'category' => $question->question?->questions_categorization?->name ?? "xxx",
                'image' => $question->question?->image,
                'type' => $question->question?->question_type?->name,
                'error' => $question->question?->error,
                'options' => $question->question?->options,
                'corrects' => $question->question?->question_type?->name != "row answers" ? $question->question?->options->where('is_correct', 1)->pluck('id')->toArray() : $question->question?->options->pluck('value', 'id')->toArray(),
                'value' => -1,
                'skipped' => null,
                'sort' => $sort,
            ];
        });
        $target = Settings::where("name", "answer target")->first();
        $answer = Answer::create([
            "quiz_id" => $id,
            "token" => Str::random(16),
            "answers" => [],
            "questions_json" => $question_json,
            "email" => $request->email ?? "",
            "user_id" => auth()?->user()?->id,
            "score" => 0,
            "timer" => $quiz->quiz_time ? Carbon::parse($quiz->quiz_time)->format('H:i:s') : null,
            "target" => $target->value,
        ]);
        $question = $answer->getQuestions()->where("sort", 1)->first();
        return redirect()->route('questions', ['token' => $answer->token, 'id' => $question["id"]]);
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
        $categories = QuestionsCategorization::all();
        return view('admin.quiz.show')->with(["quiz" => $quiz, "types" => $types, "categories" => $categories]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $folders = QuizTheme::all();
        return view('admin.quiz.updateQuiz')->with(['item' => $quiz, 'folders' => $folders]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required',
            'quiz_type' => 'required',
            'payement_type' => 'required',
            'price' => 'required_if:payement_type,==,paid,numeric'
        ]);

        if ($request->quiz_type != 1) {
            $request->validate([
                'quiz_time' => 'date_format:H:i',
                'quiz_time_remind' => 'date_format:H:i,before:quiz_time',
                'nbr_questions_sequance' => 'required_if:quiz_type,==,3',
                'break_time' => 'required_if:quiz_type,==,3'
            ]);
        }
        $quiz = Quiz::findOrFail($id);
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
        $price_token = null;
        $productToken = null;
        if (in_array($request->payement_type, [PayementTypeEnum::FREE->value, PayementTypeEnum::NONAPPLICABLE->value])) {
            $request->merge([
                'price' => null,
            ]);
        } else {
            if (in_array($quiz->payement_type, [PayementTypeEnum::FREE->value, PayementTypeEnum::NONAPPLICABLE->value])) {
                $product = $this->stripeService->createProduct($request->name, $request->price);
                $price_token = $product->default_price;
                $productToken = $product->id;
            } elseif ($request->price != $quiz->price) {
                $price = $this->stripeService->updateProductPrice($quiz->product_token, $request->price);
                $price_token = $price->id;
                $productToken = $quiz->product_token;
                Order::where('quiz_id', $quiz->id)->update(['current_price' => $request->price]);
            } else {
                $productToken = $quiz->product_token;
                $price_token = $quiz->price_token;
            }
        }

        $quiz->update([
            'quiz_type' => $request->quiz_type,
            'payement_type' => $request->payement_type,
            'price' => $request->price,
            'name' => $request->name,
            'description' => $request->description,
            'folder_id' => $request->folder === "null" ? NULL : $request->folder,
            'quiz_time' => $request->quiz_time,
            'quiz_time_remind' => $request->quiz_time_remind,
            'slug' => Str::slug($request->name),
            'image' => $request->hasFile('image') ? $filename : $quiz->image,
            'quiz_time' => $request->quiz_time,
            'quiz_time_remind' => $request->quiz_time_remind,
            'nbr_questions_sequance' => $request->nbr_questions_sequance,
            'break_time' => $request->break_time,
            'price_token' => $price_token,
            'product_token' => $productToken,
        ]);

        return redirect()->route('quiz.index')->with('status', 'Quiz updated Successfully');
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

    public function removeTimer(string $id)
    {
        $quiz = Quiz::find($id);
        $quiz->quiz_time = null;
        $quiz->quiz_time_remind = null;
        $quiz->save();
        return redirect()->back()->with('status', 'Timer deleted Successfully');
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

    public function quizExpired(string $token, string $status)
    {
        $answer = Answer::whereToken($token)->with(['quiz', 'quiz.questions', 'quiz.questions.question', 'quiz.questions.question.question_type'])->firstOrFail();
        $questions = $answer->expiredQuestions();
        $answer->questions_json = $questions;
        $answer->status = $status;
        $answer->save();
        return redirect()->route('answer', ['token' => $answer->token]);
    }

    public function order($id, $type)
    {
        $quiz =  Quiz::findOrFail($id);
        if ($type === "up") {
            $quiz->moveOrderUp();
        } else {
            $quiz->moveOrderDown();
        }
        return redirect()->route('quiz.index')->with('status', 'Quiz has been sorting');
    }

    public function payments()
    {
        $orders = Order::with('quiz', 'user')->get();
        return view('admin.orders.index', ['orders' => $orders]);
    }
}
