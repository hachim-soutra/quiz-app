<?php

namespace App\Models;

use Attribute;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable  = ['answers', 'email', 'score', 'token', 'quiz_id'];

    protected $casts = [
        'answers' => 'array'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
