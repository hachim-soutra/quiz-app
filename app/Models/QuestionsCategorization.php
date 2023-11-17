<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsCategorization extends Model
{
    use HasFactory;

    protected $table = "questions_categorizations";

    protected $fillable = ['name','color'];

    public function questions()
    {
        return $this->hasMany(config('laravel-quiz.models.question'), 'categorie_id');
    }
}
