<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicDetailsToExamSessions extends Migration
{
    public function up()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_sessions', 'details')) {
                $table->text('details')->nullable();
            }
            if (!Schema::hasColumn('exam_sessions', 'schedule')) {
                $table->text('schedule')->nullable();
            }
            if (!Schema::hasColumn('exam_sessions', 'announcement')) {
                $table->text('announcement')->nullable();
            }
            if (!Schema::hasColumn('exam_sessions', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
        });
    }

    public function down()
    {
        $columns = [];
        foreach (['details', 'schedule', 'announcement', 'is_published'] as $column) {
            if (Schema::hasColumn('exam_sessions', $column)) {
                $columns[] = $column;
            }
        }

        if ($columns) {
            Schema::table('exam_sessions', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
