<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->string('nationality')->nullable();
            $table->text('languages')->nullable();
            $table->boolean('freelance')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('nationality');
            $table->dropColumn('languages');
            $table->dropColumn('freelance');
        });
    }
}
