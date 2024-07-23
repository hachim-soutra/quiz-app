<?php

use App\Helper\Helper;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\QuestionsCategorizationController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\UserController;
use App\Models\Answer;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Cashier;

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
// guest
Route::middleware('quiz.guest')->get('/quiz/{slug}', function ($slug) {
    $quiz = Quiz::whereSlug($slug)->firstOrFail();
    $logo = Settings::where("name", "logo")->first();
    return view('quiz')->with(["quiz" => $quiz, "logo" => $logo]);
})->name('quiz');

Route::get('view-pdf/{token}', [UserController::class, 'viewPDF'])->name('view-pdf');
Route::get('download-pdf/{token}', [UserController::class, 'downloadPDF'])->name('download-pdf');

Route::get('/answer/{token}', function ($token) {
    $answer = Answer::whereToken($token)->with(['quiz', 'quiz.questions', 'quiz.questions.question', 'quiz.questions.question.question_type'])->firstOrFail();
    // chart colors
    $correct_color = Settings::where('name', 'correct answers color')->first();
    $incorrect_color = Settings::where('name', 'incorrect answers color')->first();
    $ignored_color = Settings::where('name', 'ignored answers color')->first();
    $color_below_target = Settings::where('name', 'chart color when it\'s below target')->first();
    $color_above_target = Settings::where('name', 'chart color when it\'s above target')->first();

    $correct = 0;
    $incorrect = 0;
    $ignored = 0;
    $correctAnswers = [];
    $allQuestions = [];
    foreach ($answer->getQuestions() as $question) {
        array_push($allQuestions, $question['category']);
        if ($question["value"] === null) {
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

    // To replace the target tag with value
    $below_target_text = Settings::where('name', 'below target text recap')->first();
    $above_target_text = Settings::where('name', 'above target text recap')->first();
    $count = 0;
    $terms[] = $answer->target . "%";
    $below_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
        $return = !empty($terms[$count]) ? $terms[$count] : '';
        $count++;
        return $return;
    }, $below_target_text->value);
    $above_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
        $return = !empty($terms[$count]) ? $terms[$count] : '';
        $count++;
        return $return;
    }, $above_target_text->value);
    return view('answer')->with([
        "answer" => $answer, "answersByCatego" => $answersByCatego, "allQstByCatego" => $allQstByCatego, "below_target" => $below_target, "above_target" => $above_target,
        "correct_color" => $correct_color->value, "incorrect_color" => $incorrect_color->value, "ignored_color" => $ignored_color->value, "color_below_target" => $color_below_target->value, "color_above_target" => $color_above_target->value
    ]);
})->name('answer');

Route::middleware('check.answer')->group(function () {
    Route::post('/question/show/{token}', [App\Http\Controllers\Admin\QuestionController::class, 'preview'])->name('question.preview');
    Route::post('/question/ignore/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'ignore'])->name('question.ignore');
    Route::post('/question/review/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'review'])->name('question.review');
    Route::post('/question/prev/{token}/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'prev'])->name('question.prev');
    Route::get('/questions/{token}/{id}/{pass?}', [App\Http\Controllers\Admin\QuestionController::class, 'show'])->name('questions');
    Route::post('/questions/next/{token}/{question_id}', [App\Http\Controllers\Admin\QuestionController::class, 'next'])->name('quiz.next');
});

Auth::routes();

Route::post('/quiz/create-answer/{id}', [QuizController::class, 'createAnswer'])->name('quiz.create-answer');
Route::get('/quiz/expired/{token}/{status}', [QuizController::class, 'quizExpired'])->name('quiz.expired');
Route::prefix('admin')->middleware('auth', 'admin')->group(function () {
    Route::get('/edit', [UserController::class, 'edit'])->name('admin.edit');
    Route::get('/settings/update-password', [UserController::class, 'updatePasswordView'])->name('admin.update-password');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('admin.update-account');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/answer', [App\Http\Controllers\Admin\AnswerController::class, 'index'])->name('admin.answer');
    Route::get('/answer/delete', [App\Http\Controllers\Admin\AnswerController::class, 'destroy'])->name('answer.destroy');
    Route::get('/answer/deleted-answers', [App\Http\Controllers\Admin\AnswerController::class, 'deletedAnswers'])->name('answer.deleted-answers');
    Route::get('/answer/restore-answer/{id}', [App\Http\Controllers\Admin\AnswerController::class, 'restoreAnswer'])->name('answer.restore-answer');
    Route::delete('/answer/permanent-delete/{id}', [App\Http\Controllers\Admin\AnswerController::class, 'permanentDelete'])->name('answer.permanent-delete');
    Route::get('/add-quiz', [QuizController::class, 'create'])->name('quiz.add');
    Route::get('/delete-quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.delete');
    Route::post('/quiz/import', [QuizController::class, 'import'])->name('quiz.import');
    Route::get('/quiz/timer/{id}', [QuizController::class, 'removeTimer'])->name('quiz.timer');
    Route::get('/questions/show/{id}', [QuizController::class, 'questionsShow'])->name('question.show');
    Route::post('/questions/import/{id}', [QuizController::class, 'questionsImport'])->name('questions.import');

    Route::post('/quiz/add-question/{id}', [QuizController::class, 'addQuestion'])->name('quiz.add-question');
    Route::put('/quiz/update-question/{id}', [QuizController::class, 'updateQuestion'])->name('quiz.update-question');
    Route::post('/quiz/duplicate-quiz/{id}', [QuizController::class, 'duplicateQuiz'])->name('quiz.duplicate-quiz');
    Route::get('/quiz/delete-question-video/{id}', [QuizController::class, 'deleteQuizVideo'])->name('quiz.delete-question-video');
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
    Route::get('/orders', [QuizController::class, 'payments'])->name('orders');
    Route::resource("promo", PromoController::class);
});

Route::middleware(['auth', 'client'])->group(function () {
    Route::prefix('client')->group(function () {
        Route::get('/home', [UserController::class, 'index'])->name('client.home');
        Route::get('/edit', [UserController::class, 'edit'])->name('client.edit');
        Route::get('/quizzes', [UserController::class, 'quizzes'])->name('client.quizzes');
        Route::get('/answers', [UserController::class, 'answers'])->name('answers');
        Route::get('/account', [UserController::class, 'settings'])->name('account');
        Route::get('/settings/update-password', [UserController::class, 'updatePasswordView'])->name('client.update-password');
        Route::post('/update-password', [UserController::class, 'updatePassword'])->name('client.update-account');
        Route::post('/answer/destroy', [UserController::class, 'destroy'])->name('client.answer.destroy');
        Route::get('/promos', [UserController::class, 'promos'])->name('client.promos');
        //info: checkout routes
        Route::get('/checkout/{price_token}/{product_id}/{product_type}/{query}', [UserController::class, 'checkout'])->name('checkout');
        Route::get('/checkout-success', [UserController::class, 'checkoutSuccess'])->name('checkout-success');
        Route::get('/checkout-cancel', [UserController::class, 'checkoutCancel'])->name('checkout-cancel');
        //end checkout routes

        Route::post('/save-profil', [UserController::class, 'saveUpdatedProfil'])->name('client.save-profil');

        Route::middleware('quiz.guest')->get('/quiz/{slug}', function ($slug) {
            $quiz = Quiz::whereSlug($slug)->firstOrFail();
            $logo = Settings::where("name", "logo")->first();
            return view('quiz')->with(["quiz" => $quiz, "logo" => $logo]);
        })->name('client.quiz');
    });
});
