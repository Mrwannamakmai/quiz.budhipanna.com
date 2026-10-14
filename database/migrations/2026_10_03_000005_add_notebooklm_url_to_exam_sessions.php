<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotebooklmUrlToExamSessions extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('exam_sessions', 'notebooklm_url')) {
            Schema::table('exam_sessions', function (Blueprint $table) {
                $table->string('notebooklm_url', 1000)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('exam_sessions', 'notebooklm_url')) {
            Schema::table('exam_sessions', function (Blueprint $table) {
                $table->dropColumn('notebooklm_url');
            });
        }
    }
}
