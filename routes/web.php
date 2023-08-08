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

Route::get('/seed-type', function () {
    QuestionType::all()->each(function ($q) {
        $q->delete();
    });
    QuestionType::create(['name' => 'one answer']);
    QuestionType::create(['name' => 'multiple answer']);
    QuestionType::create(['name' => 'row answers']);
});

Route::get('/quiz/{slug}', function ($slug) {
    $quiz = Quiz::whereSlug($slug)->firstOrFail();
    return view('quiz')->with(["quiz" => $quiz]);
});

Route::get('/answer/{token}', function ($token) {
    $answer = Answer::whereToken($token)->firstOrFail();
    return view('answer')->with(["answer" => $answer]);
})->name('answer');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/quiz/add-question/{id}', [QuizController::class, 'storeQuestion'])->name('quiz.store-answer');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/quiz/add-question/{id}', [QuizController::class, 'addQuestion'])->name('quiz.add-question');
    Route::post('/quiz/add-option/{id}', [QuizController::class, 'addOption'])->name('quiz.add-option');
    Route::post('/quiz/delete-question/{id}', [QuizController::class, 'removeQuestion'])->name('quiz.delete-question');
    Route::resource("quiz", QuizController::class);
});
