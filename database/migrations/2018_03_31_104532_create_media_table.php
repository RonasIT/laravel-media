<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('link');
            $table->string('name')->unique();
            $table->boolean('is_public')->default(false);

            if (config('database.default') == 'mysql') {
                $table->jsonb('meta')->nullable();
            } else {
                $table->jsonb('meta')->default('{}');
            }

            $table
                ->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on(app(config('media.classes.user_model'))->getTable())
                ->noActionOnDelete();

            $table
                ->foreignId('preview_id')
                ->nullable()
                ->references('id')
                ->on('media')
                ->noActionOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}
