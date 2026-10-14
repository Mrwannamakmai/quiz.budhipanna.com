<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * SQL equivalent (run manually on FTP-only servers):
 *
 * ALTER TABLE `users`
 *   ADD COLUMN `daily_streak`    INT(11) NOT NULL DEFAULT 0 AFTER `email_verified_at`,
 *   ADD COLUMN `longest_streak`  INT(11) NOT NULL DEFAULT 0 AFTER `daily_streak`,
 *   ADD COLUMN `last_daily_date` DATE NULL          AFTER `longest_streak`;
 */

class AddDailyStreakToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('daily_streak')->default(0)->after('email_verified_at');
            $table->integer('longest_streak')->default(0)->after('daily_streak');
            $table->date('last_daily_date')->nullable()->after('longest_streak');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_streak', 'longest_streak', 'last_daily_date']);
        });
    }
}
