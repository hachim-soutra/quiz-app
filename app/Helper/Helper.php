<?php

namespace App\Helper;

use Harishdurga\LaravelQuiz\Models\QuestionOption;

class Helper
{
    public static function compareArray($array1)
    {
        foreach ($array1 as $key => $value) {
            if (!QuestionOption::where("id", $key)->where("value", $value)->exists()) {
                return false;
            }
        }
        return true;
    }
}
