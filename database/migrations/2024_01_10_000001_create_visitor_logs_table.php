<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorLogsTable extends Migration
{
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip_address', 45);
            $table->string('url', 1000);
            $table->string('method', 10)->default('GET');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('referrer', 1000)->nullable();
            $table->datetime('visited_at');

            $table->index('ip_address');
            $table->index('visited_at');
            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_logs');
    }
}
