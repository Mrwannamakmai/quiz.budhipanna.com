<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotebooklmUrlToArticles extends Migration
{
    public function up()
    {
        // โค้ดใช้คอลัมน์ notebooklm_items (JSON หลายลิงก์) ไม่ใช่ notebooklm_url
        if (!Schema::hasColumn('articles', 'notebooklm_items')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->text('notebooklm_items')->nullable()->after('file_name');
            });
        }
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('notebooklm_items');
        });
    }
}
