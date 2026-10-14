<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * SQL equivalent (run manually on FTP-only servers):
 *
 * CREATE TABLE `daily_attempts` (
 *   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *   `user_id` bigint(20) unsigned NOT NULL,
 *   `challenge_date` date NOT NULL,
 *   `score` int(11) NOT NULL DEFAULT 0,
 *   `total` int(11) NOT NULL DEFAULT 0,
 *   `passed` tinyint(1) NOT NULL DEFAULT 0,
 *   `answers` text NULL,
 *   `streak_at` int(11) NOT NULL DEFAULT 1,
 *   `created_at` timestamp NULL,
 *   `updated_at` timestamp NULL,
 *   PRIMARY KEY (`id`),
 *   UNIQUE KEY `daily_attempts_user_date_unique` (`user_id`,`challenge_date`),
 *   CONSTRAINT `daily_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 */

class CreateDailyAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('challenge_date');
            $table->integer('score')->default(0);
            $table->integer('total')->default(0);
            $table->boolean('passed')->default(false);
            $table->text('answers')->nullable();
            $table->integer('streak_at')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'challenge_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_attempts');
    }
}
