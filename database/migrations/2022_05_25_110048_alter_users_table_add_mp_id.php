<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddMpId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedInteger('mp_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->unsignedInteger('ministry_id')->nullable();
            $table->unsignedInteger('committee_id')->nullable();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);

            $table->foreign('ministry_id','fk_users_ministry_id')->references('id')->on('ec_ministry')->onDelete('cascade');
            $table->foreign('committee_id','fk_users_committee_id')->references('id')->on('ec_committees')->onDelete('cascade');
            $table->foreign('mp_id','fk_users_mp_id')->references('id')->on('ec_mp')->onDelete('cascade');
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
            //
            $table->dropColumn('mp_id');
            $table->dropColumn('last_login');
        });
    }
}
