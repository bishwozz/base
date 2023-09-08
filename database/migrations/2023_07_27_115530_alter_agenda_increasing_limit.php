<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAgendaIncreasingLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('agenda_title', 2000)->nullable()->change();
            $table->string('agenda_description', 2000)->nullable()->change();
            $table->string('paramarsha_and_others', 2000)->nullable()->change();
            $table->string('agenda_reason_and_ministry_sipharis', 2000)->nullable()->change();
            $table->string('decision_reason', 2000)->nullable()->change();
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
