<?php

use App\Helper\Helper;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\QuestionsCategorizationController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\UserController;
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
Route::get('/login',function(){
    if(!auth::user()) {
        return redirect('/login');
    }

});

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
    $incorrect = 0;
    $ignored = 0;
    $correctAnswers = [];
    $allQuestions = [];
    foreach ($answer->getQuestions() as $question) {
        array_push($allQuestions, $question['category']);
        if ($question["value"] === -1) {
            $ignored++;
        } else {
            if ($question['type'] === 'row answers') {
                if (!is_array($question['value']) || count(array_diff($question['value'][$question['id']], $question['corrects'])) > 0) {
                    $incorrect++;
                } else {
                    array_push($correctAnswers, $question['category']);
                    $correct++;
                }
            } else {
                if (!is_array($question['value']) || count(array_diff($question['value'], $question['corrects'])) > 0) {
                    $incorrect++;
                } else {
                    array_push($correctAnswers, $question['category']);
                    $correct++;
                }
            }
        }
    }
    $answer->update([
        "nbr_of_correct" => $correct,
        "nbr_of_incorrect" => $incorrect,
        "nbr_of_ignored" => $ignored,
    ]);
    $answersByCatego = array_count_values($correctAnswers);
    $allQstByCatego = array_count_values($allQuestions);

    return view('answer')->with(["answer" => $answer, "answersByCatego" => $answersByCatego, "allQstByCatego" => $allQstByCatego]);
})->name('answer');

Route::middleware('check.answer')->group(function () {
    Route::post('/question/ignore/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'ignore'])->name('question.ignore');
    Route::post('/question/review/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'review'])->name('question.review');
    Route::post('/question/prev/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'prev'])->name('question.prev');
    Route::get('/questions/{token}/{id}/{pass?}', [App\Http\Controllers\Admin\QuestionController::class, 'show'])->name('questions');
    Route::post('/questions/next/{token}/{question_id}', [App\Http\Controllers\Admin\QuestionController::class, 'next'])->name('quiz.next');
});

Auth::routes();

Route::middleware(['auth','admin'])->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/quiz/create-answer/{id}', [QuizController::class, 'createAnswer'])->name('quiz.create-answer');
    Route::get('/quiz/expired/{token}/{status}', [QuizController::class, 'quizExpired'])->name('quiz.expired');
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/answer', [App\Http\Controllers\Admin\AnswerController::class, 'index'])->name('admin.answer');
        Route::post('/answer/delete', [App\Http\Controllers\Admin\AnswerController::class, 'destroy'])->name('answer.destroy');
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
});

Route::middleware(['auth','user'])->group(function(){
    Route::prefix('user')->middleware('auth')->group(function () {
        Route::get('/home',[UserController::class,'index'])->name('home');
        Route::get('/account',[UserController::class,'settings'])->name('account');
        Route::post('/update-account/{user}',[UserController::class,'updateAccount'])->name('update-account');
    });
});

