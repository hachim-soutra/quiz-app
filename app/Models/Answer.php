<?php

namespace App\Models;

use Attribute;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded  = [];

    protected $casts = [
        'answers' => 'array',
        'quiz_time' => 'datetime',
        'questions_json' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function getScoreAttribute()
    {
        return $this->answers ? $this->nbr_of_correct * 100 / count($this->answers) : 0;
    }

    public function getQuestion($id)
    {
        $question = collect($this->questions_json)->where('id', $id)->first();
        return $this->questions_json ? $question : null;
    }

    public function getQuestions()
    {
        return collect($this->questions_json);
    }

    public function setQuestion($id, $value)
    {
        $qu = [];
        foreach ($this->questions_json as $q) {
            if ($q["id"] == $id) {
                $q["value"] = $value;
            }
            $qu[] = $q;
        }
        return $qu;
    }
}
