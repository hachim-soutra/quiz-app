<?php

namespace App\Models;

use Attribute;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable  = ['answers', 'email', 'token', 'quiz_id', 'nbr_of_correct'];

    protected $casts = [
        'answers' => 'array'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function getScoreAttribute()
    {
        return $this->answers ? $this->nbr_of_correct * 100 / count($this->answers) : 0;
    }
}
