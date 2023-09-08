<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMinistryEmployeeForUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_ministry_employees', function (Blueprint $table) {
            //
            $table->string('phone_number')->unique()->change();
        });
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('phone_no')->unique()->change();
        });
        Schema::table('ec_mp', function (Blueprint $table) {
            //
            $table->string('mobile_number')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_ministry_employees', function (Blueprint $table) {
            //
        });
    }
}
