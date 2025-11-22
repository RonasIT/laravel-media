<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $deleteConstraint = config('media.on_delete_constraint', 'no action');

            $this->changeForeignKeyConstraint(
                table: $table,
                keyColumnName: 'owner_id',
                referencedTable: app(config('media.classes.user_model'))->getTable(),
                deleteConstraint: $deleteConstraint,
            );
            $this->changeForeignKeyConstraint(
                table: $table,
                keyColumnName: 'preview_id',
                referencedTable: 'media',
                deleteConstraint: $deleteConstraint,
            );
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $this->changeForeignKeyConstraint(
                table: $table,
                keyColumnName: 'owner_id',
                referencedTable: app(config('media.classes.user_model'))->getTable(),
            );
            $this->changeForeignKeyConstraint(
                table: $table,
                keyColumnName: 'preview_id',
                referencedTable: 'media',
            );
        });
    }

    protected function changeForeignKeyConstraint(
        Blueprint $table,
        string $keyColumnName,
        string $referencedTable,
        string $deleteConstraint = 'no action',
    ): void {
        $table->dropForeign([$keyColumnName]);

        $table
            ->foreign($keyColumnName)
            ->references('id')
            ->on($referencedTable)
            ->onDelete($deleteConstraint);
    }
};
