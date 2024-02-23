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

    protected $casts = [
        'select_quizzes' => 'array',
    ];

    public function quizzes()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz'), 'folder_id')->orderBy('name');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('label', 'LIKE', "%$search%");
    }
}
