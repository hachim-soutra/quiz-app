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
            $table->time('quiz_time')->nullable();
            $table->time('quiz_time_remind')->nullable();
            $table->string('quiz_type')->default(1);
            $table->integer('nbr_questions_sequance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('quiz_time');
            $table->dropColumn('quiz_time_remind');
            $table->dropColumn('quiz_type');
            $table->dropColumn('nbr_questions_sequance');
        });
    }
};
