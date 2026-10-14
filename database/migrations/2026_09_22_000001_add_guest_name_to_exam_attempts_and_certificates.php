<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuestNameToExamAttemptsAndCertificates extends Migration
{
    public function up()
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->string('guest_name', 255)->nullable()->after('user_id');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->string('guest_name', 255)->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn('guest_name');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('guest_name');
        });
    }
}
