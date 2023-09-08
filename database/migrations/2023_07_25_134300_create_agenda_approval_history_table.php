<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaApprovalHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_approval_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('agenda_id');
            $table->unsignedSmallInteger('role_id');
            $table->unsignedSmallInteger('status_id');
            $table->string('date_bs',10)->nullable();
            $table->date('date_ad')->nullable();
            $table->string('remarks',5000)->nullable();


            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('role_id','fk_agenda_approval_history_role_id')->references('id')->on('roles');
            $table->foreign('agenda_id','fk_agenda_approval_history_agenda_id')->references('id')->on('agendas');
        });

        Schema::table('agendas', function (Blueprint $table) {
            //
            $table->unsignedInteger('level_id')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_approaval_history');
    }
}
