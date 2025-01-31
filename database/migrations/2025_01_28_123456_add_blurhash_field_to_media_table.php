<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlurhashFieldToMediaTable extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('preview_hash')->nullable();
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('preview_hash');
        });
    }
}