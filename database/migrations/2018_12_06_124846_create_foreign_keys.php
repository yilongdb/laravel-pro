<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
        Schema::table('components', function(Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });

        Schema::table('tokens', function(Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });

        Schema::table('layers', function(Blueprint $table) {
            $table->foreign('component_id')->references('id')->on('components')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function(Blueprint $table) {
            $table->dropForeign('files_user_id_foreign');
        });
        Schema::table('components', function(Blueprint $table) {
            $table->dropForeign('components_file_id_foreign');
        });
        Schema::table('tokens', function(Blueprint $table) {
            $table->dropForeign('tokens_file_id_foreign');
        });
        Schema::table('layers', function(Blueprint $table) {
            $table->dropForeign('layers_component_id_foreign');
        });
    }
}
