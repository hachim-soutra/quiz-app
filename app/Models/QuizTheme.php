<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTheme extends Model
{
    use HasFactory;

    protected $table = 'folders';

    protected $guarded = [];

    protected $fillable = ['label'];

    public function quizzes()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz'),'folder_id');
    }
}
