<?php

namespace App\Console\Commands;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SortQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sort-question';

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
        $quizzes =  Quiz::all();

        foreach ($quizzes as $quiz) {
            $order = 1;
            foreach ($quiz->questions()->orderBy('order', 'ASC')->get() as $q) {
                $q->order = $order;
                $q->save();
                $order++;
            }
        }
    }
}
