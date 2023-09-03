<?php

use App\Http\Controllers\Admin\QuizController;
use App\Models\Answer;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quiz/{slug}', function ($slug) {
    $quiz = Quiz::whereSlug($slug)->firstOrFail();
    return view('quiz')->with(["quiz" => $quiz]);
})->name('quiz');

Route::get('/answer/{token}', function ($token) {
    $answer = Answer::whereToken($token)->firstOrFail();
    return view('answer')->with(["answer" => $answer]);
})->name('answer');

Route::get('/questions/{token}/{id}', function ($token, $id) {
    $answer = Answer::with("quiz")->whereToken($token)->firstOrFail();
    $question = Question::findOrFail($id);
    return view('question')->with(["answer" => $answer, "question" => $question]);
})->name('questions');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/quiz/add-question/{id}/{question_id}', [QuizController::class, 'storeQuestion'])->name('quiz.store-answer');
Route::post('/quiz/create-answer/{id}', [QuizController::class, 'createAnswer'])->name('quiz.create-answer');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/answer', [App\Http\Controllers\Admin\AnswerController::class, 'index'])->name('admin.answer');

    Route::post('/quiz/import', [QuizController::class, 'import'])->name('quiz.import');
    Route::get('/questions/show/{id}', [QuizController::class, 'questionsShow'])->name('question.show');
    Route::post('/questions/import/{id}', [QuizController::class, 'questionsImport'])->name('questions.import');

    Route::post('/quiz/add-question/{id}', [QuizController::class, 'addQuestion'])->name('quiz.add-question');
    Route::put('/quiz/update-question/{id}', [QuizController::class, 'updateQuestion'])->name('quiz.update-question');
    Route::post('/quiz/duplicate-quiz/{id}', [QuizController::class, 'duplicateQuiz'])->name('quiz.duplicate-quiz');
    Route::post('/quiz/duplicate-question/{id}/{qst_id}', [QuizController::class, 'duplicateQuestion'])->name('quiz.duplicate-question');
    Route::post('/quiz/delete-question/{id}', [QuizController::class, 'removeQuestion'])->name('quiz.delete-question');
    Route::post('/quiz/delete-all/{id}', [QuizController::class, 'removeAllQuestions'])->name('quiz.delete-all');

    Route::post('/quiz/add-option/{id}', [QuizController::class, 'addOption'])->name('quiz.add-option');
    Route::put('/quiz/update-option/{id}', [QuizController::class, 'updateOption'])->name('quiz.update-option');
    Route::post('/quiz/delete-option/{id}', [QuizController::class, 'removeOption'])->name('quiz.delete-option');
    Route::resource("quiz", QuizController::class);
});
