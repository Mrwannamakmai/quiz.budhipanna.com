<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrdExamRegistrationTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('srd_exam_centers')) {
            Schema::create('srd_exam_centers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->text('address')->nullable();
                $table->string('province', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('subdistrict', 100)->nullable();
                $table->string('postal_code', 10)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('srd_exam_sessions')) {
            Schema::create('srd_exam_sessions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->date('exam_date')->nullable();
                $table->string('academic_year', 20)->nullable();
                $table->text('details')->nullable();
                $table->text('schedule')->nullable();
                $table->text('announcement')->nullable();
                $table->boolean('is_published')->default(false);
                $table->string('notebooklm_url', 1000)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('srd_exam_registrations')) {
            Schema::create('srd_exam_registrations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('reference_code', 30)->unique();

                $table->string('academic_year', 20)->nullable();
                $table->string('title', 50)->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->unsignedSmallInteger('age')->nullable();
                $table->unsignedTinyInteger('dob_day')->nullable();
                $table->unsignedTinyInteger('dob_month')->nullable();
                $table->unsignedSmallInteger('dob_year_be')->nullable();

                $table->string('citizen_id', 13)->nullable();

                $table->string('address_no', 100)->nullable();
                $table->string('moo', 50)->nullable();
                $table->string('subdistrict', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('province', 100)->nullable();
                $table->string('postal_code', 10)->nullable();

                $table->string('education_level', 100)->nullable();

                $table->string('previous_certificate_no', 100)->nullable();
                $table->string('previous_level', 50)->nullable();
                $table->string('previous_range', 50)->nullable();
                $table->unsignedSmallInteger('previous_passed_year_be')->nullable();
                $table->string('previous_province', 100)->nullable();

                $table->string('target_level', 50)->nullable();
                $table->string('target_range', 50)->nullable();

                $table->string('office_name')->nullable();
                $table->string('office_subdistrict', 100)->nullable();
                $table->string('office_district', 100)->nullable();
                $table->string('office_province', 100)->nullable();
                $table->string('office_postal_code', 10)->nullable();

                $table->string('status', 20)->default('PENDING');
                $table->string('approved_by')->nullable();
                $table->timestamp('approved_date')->nullable();
                $table->timestamp('submitted_date')->nullable();

                $table->text('referral_sources')->nullable();
                $table->string('follow_up_status', 30)->nullable();

                $table->string('phone', 20)->nullable();
                $table->string('line_id', 100)->nullable();

                $table->unsignedBigInteger('exam_session_id')->nullable();
                $table->unsignedBigInteger('exam_center_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();

                $table->timestamps();

                $table->index('citizen_id');
                $table->index('status');

                $table->foreign('exam_session_id')->references('id')->on('srd_exam_sessions')->onDelete('set null');
                $table->foreign('exam_center_id')->references('id')->on('srd_exam_centers')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('srd_exam_followups')) {
            Schema::create('srd_exam_followups', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('exam_registration_id');
                $table->timestamp('followed_at')->nullable();
                $table->string('channel', 50)->nullable();
                $table->string('result', 50)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('followed_by')->nullable();
                $table->timestamps();

                $table->foreign('exam_registration_id')->references('id')->on('srd_exam_registrations')->onDelete('cascade');
                $table->foreign('followed_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('srd_exam_followups');
        Schema::dropIfExists('srd_exam_registrations');
        Schema::dropIfExists('srd_exam_sessions');
        Schema::dropIfExists('srd_exam_centers');
    }
}
