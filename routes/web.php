<?php

use App\Helper\Helper;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\QuestionsCategorizationController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\SettingsController;
use App\Models\Answer;
use App\Models\Settings;
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
    $logo_home = Settings::where("name", "home page logo")->first();
    return view('welcome')->with(["logo_home" => $logo_home]);
});

Route::get('/quiz/{slug}', function ($slug) {
    $quiz = Quiz::whereSlug($slug)->firstOrFail();
    $logo = Settings::where("name", "logo")->first();
    return view('quiz')->with(["quiz" => $quiz, "logo" => $logo]);
})->name('quiz');

Route::get('/answer/{token}', function ($token) {
    $answer = Answer::whereToken($token)->with(['quiz', 'quiz.questions', 'quiz.questions.question', 'quiz.questions.question.question_type'])->firstOrFail();
    $correct = 0;
    foreach ($answer->quiz->questions as $question) {
        if ($question && $question->question && $question->question->question_type) {
            if ($question->question->question_type->name === 'row answers') {
                if (
                    isset($answer->answers[$question->question->id]) &&
                    Helper::compareArray($answer->answers[$question->question->id])
                ) {
                    $correct++;
                }
            } else {
                if (
                    isset($answer->answers[$question->question->id]) &&
                    count(array_diff(
                        $question->question->options()->where('is_correct', 1)->pluck('id')->toArray(),
                        array_values($answer->answers[$question->question->id])
                    )) == 0 &&
                    count(array_diff(
                        array_values($answer->answers[$question->question->id]),
                        $question->question->options()->where('is_correct', 1)->pluck('id')->toArray()
                    )) === 0
                ) {
                    $correct++;
                }
            }
        }
    }
    $answer->update([
        "nbr_of_correct" => $correct
    ]);
    $logo = Settings::where("name", "logo")->first();
    return view('answer')->with(["answer" => $answer, "logo" => $logo]);
})->name('answer');

Route::get('/question/next/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'next'])->name('question.next');
Route::get('/question/prev/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'prev'])->name('question.prev');
Route::get('/questions/{token}/{id}/{pass?}', [App\Http\Controllers\Admin\QuestionController::class, 'show'])->name('questions');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/quiz/add-question/{id}/{question_id}', [QuizController::class, 'storeQuestion'])->name('quiz.store-answer');
Route::post('/quiz/create-answer/{id}', [QuizController::class, 'createAnswer'])->name('quiz.create-answer');
Route::get('/quiz/expired/{token}/{status}', [QuizController::class, 'quizExpired'])->name('quiz.expired');
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/answer', [App\Http\Controllers\Admin\AnswerController::class, 'index'])->name('admin.answer');
    Route::delete('/answer/delete/{id}', [App\Http\Controllers\Admin\AnswerController::class, 'destroy'])->name('answer.destroy');
    Route::get('/answer/deleted-answers', [App\Http\Controllers\Admin\AnswerController::class, 'deletedAnswers'])->name('answer.deleted-answers');
    Route::get('/answer/restore-answer/{id}', [App\Http\Controllers\Admin\AnswerController::class, 'restoreAnswer'])->name('answer.restore-answer');
    Route::delete('/answer/permanent-delete/{id}', [App\Http\Controllers\Admin\AnswerController::class, 'permanentDelete'])->name('answer.permanent-delete');
    Route::get('/add-quiz', [QuizController::class, 'create'])->name('quiz.add');
    Route::post('/quiz/import', [QuizController::class, 'import'])->name('quiz.import');
    Route::get('/quiz/timer/{id}', [QuizController::class, 'removeTimer'])->name('quiz.timer');
    Route::get('/questions/show/{id}', [QuizController::class, 'questionsShow'])->name('question.show');
    Route::post('/questions/import/{id}', [QuizController::class, 'questionsImport'])->name('questions.import');

    Route::post('/quiz/add-question/{id}', [QuizController::class, 'addQuestion'])->name('quiz.add-question');
    Route::put('/quiz/update-question/{id}', [QuizController::class, 'updateQuestion'])->name('quiz.update-question');
    Route::post('/quiz/duplicate-quiz/{id}', [QuizController::class, 'duplicateQuiz'])->name('quiz.duplicate-quiz');
    Route::post('/quiz/duplicate-question/{id}/{qst_id}', [QuizController::class, 'duplicateQuestion'])->name('quiz.duplicate-question');
    Route::post('/quiz/remove-question-image/{id}', [QuizController::class, 'removeQuestionImage'])->name('quiz.remove-question-img');
    Route::post('/quiz/delete-question/{id}', [QuizController::class, 'removeQuestion'])->name('quiz.delete-question');
    Route::post('/quiz/delete-all/{id}', [QuizController::class, 'removeAllQuestions'])->name('quiz.delete-all');

    Route::post('/quiz/add-option/{id}', [QuizController::class, 'addOption'])->name('quiz.add-option');
    Route::put('/quiz/update-option/{id}', [QuizController::class, 'updateOption'])->name('quiz.update-option');
    Route::post('/quiz/delete-option/{id}', [QuizController::class, 'removeOption'])->name('quiz.delete-option');
    Route::get('/quiz/order/{id}/{type}', [QuizController::class, 'order'])->name('quiz.order');
    Route::resource("quiz", QuizController::class);
    Route::resource("categorie", QuestionsCategorizationController::class);
    Route::resource("settings", SettingsController::class);
    Route::get('/question/sort/{id}/{type}', [App\Http\Controllers\Admin\QuestionController::class, 'sort'])->name('question.sort');
    Route::resource("folder", FolderController::class);
});
