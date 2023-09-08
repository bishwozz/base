<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployeeMemberAndEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_ministry_member')->default(false);
        });

        Schema::table('ec_ministry_members', function (Blueprint $table) {
            $table->boolean('allow_user_login')->default(false);
            $table->unsignedSmallInteger('role_id')->nullable();

            $table->foreign('role_id','fk_ec_ministry_members_role_id')->references('id')->on('roles');

        });

        Schema::table('ec_ministry_employees', function (Blueprint $table) {
            $table->boolean('allow_user_login')->default(false);
            $table->unsignedSmallInteger('role_id')->nullable();

            $table->foreign('role_id','fk_ec_ministry_employees_role_id')->references('id')->on('roles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
