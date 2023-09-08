<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSachivIdToAgendaHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda_histories', function (Blueprint $table) {
            //
            $table->unsignedSmallInteger('pramukh_sachiv_id')->nullable();

            $table->foreign('pramukh_sachiv_id','fk_agenda_histories_employee_id')->references('id')->on('ec_ministry_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda_histories', function (Blueprint $table) {
            //
        });
    }
}
