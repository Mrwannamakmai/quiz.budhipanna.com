<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublicRegistrationFields extends Migration
{
    public function up()
    {
        Schema::table('exam_registrations', function (Blueprint $table) {
            $table->string('reference_code', 30)->nullable()->unique()->after('id');
            $table->string('phone', 20)->nullable()->after('previous_certificate_no');
            $table->string('line_id', 100)->nullable()->after('phone');
            $table->unsignedTinyInteger('age')->nullable()->after('line_id');
            $table->unsignedTinyInteger('birth_day')->nullable()->after('age');
            $table->unsignedTinyInteger('birth_month')->nullable()->after('birth_day');
            $table->unsignedSmallInteger('birth_year_be')->nullable()->after('birth_month');
            $table->string('house_number', 100)->nullable()->after('birth_year_be');
            $table->string('sub_district', 100)->nullable()->after('house_number');
            $table->string('district', 100)->nullable()->after('sub_district');
            $table->string('province', 100)->nullable()->after('district');
            $table->string('postal_code', 5)->nullable()->after('province');
            $table->string('education_level', 50)->nullable()->after('postal_code');
            $table->boolean('has_previous_cert')->default(false)->after('education_level');
            $table->string('previous_level', 20)->nullable()->after('has_previous_cert');
            $table->string('previous_period', 50)->nullable()->after('previous_level');
            $table->string('previous_year', 10)->nullable()->after('previous_period');
            $table->string('previous_province', 100)->nullable()->after('previous_year');
            $table->string('office_name', 255)->nullable()->after('previous_province');
            $table->string('office_sub_district', 100)->nullable()->after('office_name');
            $table->string('office_district', 100)->nullable()->after('office_sub_district');
            $table->string('office_province', 100)->nullable()->after('office_district');
            $table->string('office_postal_code', 5)->nullable()->after('office_province');
            $table->text('referral_sources')->nullable()->after('office_postal_code');
            $table->string('registration_channel', 20)->default('internal')->after('referral_sources');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('registration_mode', 20)->default('auto')->after('is_published');
            $table->date('registration_start_date')->nullable()->after('registration_mode');
            $table->date('registration_end_date')->nullable()->after('registration_start_date');
        });
    }

    public function down()
    {
        Schema::table('exam_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'reference_code', 'phone', 'line_id', 'age',
                'birth_day', 'birth_month', 'birth_year_be',
                'house_number', 'sub_district', 'district', 'province', 'postal_code',
                'education_level', 'has_previous_cert',
                'previous_level', 'previous_period', 'previous_year', 'previous_province',
                'office_name', 'office_sub_district', 'office_district', 'office_province', 'office_postal_code',
                'referral_sources', 'registration_channel',
            ]);
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn(['registration_mode', 'registration_start_date', 'registration_end_date']);
        });
    }
}
