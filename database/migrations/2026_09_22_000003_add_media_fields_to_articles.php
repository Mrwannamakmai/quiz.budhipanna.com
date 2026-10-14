<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaFieldsToArticles extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('image_url', 500)->nullable()->after('body');
            $table->string('youtube_url', 500)->nullable()->after('image_url');
            $table->string('canva_url', 500)->nullable()->after('youtube_url');
            $table->string('ebook_url', 500)->nullable()->after('canva_url');
            $table->string('file_url', 500)->nullable()->after('ebook_url');
            $table->string('file_name', 255)->nullable()->after('file_url');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['image_url','youtube_url','canva_url','ebook_url','file_url','file_name']);
        });
    }
}
