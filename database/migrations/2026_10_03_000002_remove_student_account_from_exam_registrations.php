<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStudentAccountFromExamRegistrations extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('exam_registrations', 'student_id')) {
            Schema::table('exam_registrations', function (Blueprint $table) {
                $table->dropForeign('exam_registrations_student_id_foreign');
                $table->dropColumn('student_id');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('exam_registrations', 'student_id')) {
            Schema::table('exam_registrations', function (Blueprint $table) {
                $table->unsignedBigInteger('student_id')->nullable();
                $table->foreign('student_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }
}
