<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('attendance_sessions')) {
            Schema::create('attendance_sessions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('classroom_id');
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->date('session_date');
                $table->time('start_time')->nullable();
                $table->string('title', 150)->nullable();
                $table->text('note')->nullable();
                $table->string('checkin_code', 12)->nullable();
                $table->timestamp('checkin_opened_at')->nullable();
                $table->timestamp('checkin_closes_at')->nullable();
                $table->timestamps();

                $table->index(['classroom_id', 'session_date']);
                $table->index('checkin_code');
                $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('attendance_records')) {
            Schema::create('attendance_records', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('attendance_session_id');
                $table->unsignedBigInteger('user_id');
                $table->string('status', 20);            // present | late | leave | absent
                $table->string('method', 10)->default('teacher'); // teacher | self
                $table->timestamp('checked_in_at')->nullable();
                $table->string('note', 255)->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->unique(['attendance_session_id', 'user_id']);
                $table->index('user_id');
                $table->foreign('attendance_session_id')->references('id')->on('attendance_sessions')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendance_sessions');
    }
}
