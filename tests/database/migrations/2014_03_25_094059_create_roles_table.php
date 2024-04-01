<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        $this->createTable();
        $this->addRoles();
    }

    public function down(): void
    {
        Schema::drop('roles');
    }

    public function createTable(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function addRoles(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'administrator',
            ],
            [
                'id' => 2,
                'name' => 'user',
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
