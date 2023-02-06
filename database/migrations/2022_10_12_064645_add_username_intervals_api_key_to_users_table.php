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

             $table->after('name', function ($table) {

                $table->string('intervals_user_name')->unique();
                $table->string('intervals_api_key')->unique();
            });
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

            $table->dropUnique('intervals_user_name');
            $table->dropUnique('intervals_api_key');

            $table->dropColumn('intervals_user_name');
            $table->dropColumn('intervals_api_key');
        });
    }
};
