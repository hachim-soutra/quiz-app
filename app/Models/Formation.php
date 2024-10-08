<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Formation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $indexedQuizzes;

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class);
    }

    public function product(): MorphOne
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function getQuizzesByIndex()
    {

        $this->indexedQuizzes = $this->quizzes->map(function ($quiz, $index) {
            return [
                'index' => $index,
                'quiz' => $quiz,
            ];
        });

        return $this->indexedQuizzes;
    }

    public function getQuiz($id)
    {
        $quiz = $this->indexedQuizzes->filter(function ($item) use ($id) {
            return (string) $item['quiz']->id === (string) $id;
        })->first();
        return $quiz;
    }

}
