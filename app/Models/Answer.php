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
        return $this->questions_json ? $this->nbr_of_correct * 100 / count($this->questions_json) : 0;
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

    public function haveRightToPrev($sort)
    {
        // if quiz type is different of simuler show btn preview
        if ($this->quiz->quiz_type != 3) return true;
        // if there is no break show btn preview
        if (!$this->nbr_of_breaks) return true;
        // if it's not the qst after break show preview (in case we take break after 1 questions or more)
        if ($this->nbr_of_breaks * $this->quiz->nbr_questions_sequance < $sort - 1 && ($this->nbr_of_breaks * $this->quiz->nbr_questions_sequance) * 2 > $sort ) return true;
        // if it's not the qst after break show preview (in case we take break after 2 questions)
        if ($this->quiz->nbr_questions_sequance == 2) {
            if ($this->nbr_of_breaks * $this->quiz->nbr_questions_sequance < $sort + 1) return true;
        }
        // if sort more then break * nbr of sequences
        return $this->nbr_of_breaks * $this->quiz->nbr_questions_sequance < $sort - 1;
    }

    public function getQuestionsIgnored()
    {
        return $this->getQuestions()->whereNull("value")->whereNull("skipped");
    }

    public function getQuestionsReview()
    {
        return $this->getQuestions()->where("value", "review");
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

    public function expiredQuestions()
    {
        $qu = [];
        foreach ($this->questions_json as $q) {
            if ($q["value"] == -1) {
                $q["value"] = null;
            }
            $qu[] = $q;
        }
        return $qu;
    }

    public function setSkipped($sort)
    {
        $qu = [];
        foreach ($this->questions_json as $q) {
            if ($q["sort"] <= $sort) {
                $q["skipped"] = true;
            }
            $qu[] = $q;
        }
        return $qu;
    }
}
