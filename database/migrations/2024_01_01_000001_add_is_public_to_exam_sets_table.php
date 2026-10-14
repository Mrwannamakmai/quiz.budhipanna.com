<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPublicToExamSetsTable extends Migration
{
    public function up()
    {
        Schema::table('exam_sets', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('exam_sets', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
}
