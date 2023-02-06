<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('client')->nullable()->change();
            $table->string('clientid')->nullable()->change();
            $table->string('groupid')->nullable()->change();
            $table->string('numlogins')->nullable()->change();
            $table->string('clientlocalid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->bigInteger('clientid')->change();
            $table->bigInteger('groupid')->change();
            $table->bigInteger('numlogins')->change();
            $table->bigInteger('clientlocalid')->change();
        });
    }
};
