<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Quiz;
use Illuminate\Console\Command;

class UpdateQuizzesWithProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:quizzes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing quizzes with the product relationship';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quizzes = Quiz::doesntHave('product')->get();

        foreach ($quizzes as $quiz)
        {
            $product = new Product();
            $quiz->product()->save($product);
        }

        $this->info('Quizzes updated successfully.');
    }
}
