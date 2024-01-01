<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Settings;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $setting = new Settings();
        // $setting->name = "logo";
        // $setting->value = "";
        // $setting->save();
        // DB::table('folders')->insert([
        //     [
        //         'id' => 9999,
        //         'label' => 'uncategorized',
        //     ]
        // ]);
        Quiz::whereNull('folder_id')->update(['folder_id' => 9999]);
    }
}
