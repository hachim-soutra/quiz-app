<?php

namespace App\Console\Commands;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Console\Command;

class QuizSort extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:quiz-sort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quizzes = Quiz::all();

        $i = 1;
        foreach ($quizzes as $quiz) {
            $quiz->order = $i;
            $quiz->save();
            $i++;
        }
    }
}
