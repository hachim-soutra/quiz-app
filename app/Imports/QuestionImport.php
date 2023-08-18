<?php

namespace App\Imports;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Str;

class QuestionImport implements ToModel, WithStartRow
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $quiz = Quiz::whereId($this->id)->firstOrFail();
        $question = Question::create([
            'name' => $row[0],
            'question_type_id' => $row[1],
            'is_active' => true
        ]);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
        ]);

        $optionsAnswer = explode(",", $row[3]);

        foreach (explode("@", $row[2]) as $key => $value) {
            QuestionOption::create([
                'question_id' => $question->id,
                'name' => $value,
                'is_correct' => $optionsAnswer[$key] == 1
            ]);
        }
    }
}
