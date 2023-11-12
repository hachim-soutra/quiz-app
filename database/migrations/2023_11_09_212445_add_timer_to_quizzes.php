<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->time('break_time')->nullable();
            $table->time('quiz_time')->nullable();
            $table->time('quiz_time_remind')->nullable();
            $table->string('quiz_time_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('break_time');
            $table->dropColumn('quiz_time');
            $table->dropColumn('quiz_time_remind');
            $table->dropColumn('quiz_time_status');
        });
    }
};
