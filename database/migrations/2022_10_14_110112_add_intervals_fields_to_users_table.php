<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   /*  "me": [
        {
            "id": "374199",
            "localid": "171",
            "clientid": "366235",
            "title": "",
            "firstname": "Muhammad Umer",
            "lastname": "Farooq",
            "primaryaccount": "f",
            "notes": "",
            "allprojects": "f",
            "private": "f",
            "tips": "f",
            "username": "umer.farooq",
            "groupid": "4",
            "group": "Resource",
            "client": "CubiVerse",
            "numlogins": "13",
            "lastlogin": "2022-10-14 10:26:20",
            "timezone": "Asia/Karachi",
            "timezone_offset": "Islamabad, Karachi, Tashkent",
            "restricttasks": "f",
            "clientlocalid": "36",
            "calendarorientation": "1",
            "editordisabled": "0"
        } */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

             $table->after('email', function ($table) {

                $table->bigInteger('intervals_id')->unique();
                $table->string('localid');
                $table->bigInteger('clientid');
                $table->string('title');
                $table->string('firstname');
                $table->string('lastname');
                $table->enum('primaryaccount',['f','t']);
                $table->string('notes');
                $table->enum('allprojects',['f','t']);
                $table->enum('private',['f','t']);
                $table->enum('tips',['f','t']);
                $table->bigInteger('groupid');
                $table->string('group');
                $table->string('client');
                $table->bigInteger('numlogins');
                $table->timestamp('lastlogin')->nullable();
                $table->string('timezone');
                $table->string('timezone_offset');
                $table->enum('restricttasks',['f','t']);
                $table->bigInteger('clientlocalid');
                $table->string('calendarorientation');
                $table->string('editordisabled');

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

            $table->dropUnique('intervals_id');

            $table->dropColumn('intervals_id');
            $table->dropColumn('localid');
            $table->dropColumn('clientid');
            $table->dropColumn('title');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('primaryaccount');
            $table->dropColumn('notes');
            $table->dropColumn('allprojects');
            $table->dropColumn('private');
            $table->dropColumn('tips');
            $table->dropColumn('groupid');
            $table->dropColumn('group');
            $table->dropColumn('client');
            $table->dropColumn('numlogins');
            $table->dropColumn('lastlogin');
            $table->dropColumn('timezone');
            $table->dropColumn('timezone_offset');
            $table->dropColumn('restricttasks');
            $table->dropColumn('clientlocalid');
            $table->dropColumn('calendarorientation');
            $table->dropColumn('editordisabled');
        });
    }
};
