<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);

            $table
                ->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on(app(config('media.classes.user_model'))->getTable())
                ->onDelete(config('media.on_delete_constraint', 'no action'));

            $table->dropForeign(['preview_id']);

            $table
                ->foreignId('preview_id')
                ->nullable()
                ->references('id')
                ->on('media')
                ->onDelete(config('media.on_delete_constraint', 'no action'));
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);

            $table
                ->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on(app(config('media.classes.user_model'))->getTable())
                ->noActionOnDelete();

            $table->dropForeign(['preview_id']);

            $table
                ->foreignId('preview_id')
                ->nullable()
                ->references('id')
                ->on('media')
                ->noActionOnDelete();
        });
    }
};
