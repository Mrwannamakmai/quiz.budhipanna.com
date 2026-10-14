<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * SQL equivalent (FTP-only servers):
 *
 * CREATE TABLE `system_updates` (
 *   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *   `title`        varchar(255) NOT NULL,
 *   `description`  text NULL,
 *   `type`         varchar(30)  NOT NULL DEFAULT 'feature',
 *   `is_published` tinyint(1)   NOT NULL DEFAULT 1,
 *   `published_at` datetime NULL,
 *   `created_by`   bigint(20) unsigned NULL,
 *   `created_at`   timestamp NULL,
 *   `updated_at`   timestamp NULL,
 *   PRIMARY KEY (`id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 */

class CreateSystemUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('system_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type', 30)->default('feature'); // feature | improvement | fix | content
            $table->boolean('is_published')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_updates');
    }
}
