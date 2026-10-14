<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradingColumnsToStudentAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('student_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('student_answers', 'is_graded')) {
                $table->boolean('is_graded')->default(false)->after('points_earned');
            }
            if (!Schema::hasColumn('student_answers', 'grader_comment')) {
                $table->text('grader_comment')->nullable()->after('is_graded');
            }
            if (!Schema::hasColumn('student_answers', 'essay_answer')) {
                $table->text('essay_answer')->nullable()->after('answer');
            }
        });
    }

    public function down()
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropColumn(['is_graded', 'grader_comment', 'essay_answer']);
        });
    }
}
