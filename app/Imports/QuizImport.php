<?php

namespace App\Imports;

use App\Models\Quiz;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Str;

class QuizImport implements ToModel, WithStartRow, WithCustomCsvSettings
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
            'delimiter' => ","
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Quiz([
            'name' => $row[0],
            'description' => $row[1],
            'slug' => Str::slug($row[0]),
            'is_published' => 1
        ]);
    }
}
