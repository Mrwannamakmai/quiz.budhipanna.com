<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRegistrationTables extends Migration
{
    public function up()
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('academic_year', 20);
            $table->date('exam_date')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });

        Schema::create('exam_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('exam_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exam_session_id');
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('exam_center_id')->nullable();
            $table->string('national_id', 13);
            $table->string('title', 50);
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('education_class', 50);
            $table->string('exam_tier', 10);
            $table->string('previous_certificate_no', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->string('status', 20)->default('registered');
            $table->timestamps();

            $table->unique(['exam_session_id', 'national_id']);
            $table->foreign('exam_session_id')->references('id')->on('exam_sessions')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('exam_center_id')->references('id')->on('exam_centers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_registrations');
        Schema::dropIfExists('exam_centers');
        Schema::dropIfExists('exam_sessions');
    }
}
