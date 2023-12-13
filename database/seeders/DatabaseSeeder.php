<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Settings;
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
        DB::table('settings')->insert([
            [
                'name' => 'answer target',
                'value' => '75',
                'type' => 'int',
            ]
        ]);
    }
}
