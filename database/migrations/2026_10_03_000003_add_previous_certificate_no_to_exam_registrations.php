<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreviousCertificateNoToExamRegistrations extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('exam_registrations', 'previous_certificate_no')) {
            Schema::table('exam_registrations', function (Blueprint $table) {
                $table->string('previous_certificate_no', 100)->nullable()->after('exam_tier');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('exam_registrations', 'previous_certificate_no')) {
            Schema::table('exam_registrations', function (Blueprint $table) {
                $table->dropColumn('previous_certificate_no');
            });
        }
    }
}
