<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ec_meeting_request_id')->nullable();
            $table->unsignedInteger('agenda_id')->nullable();
            $table->integer('transfered_to')->nullable();
            $table->unsignedSmallInteger('step_id')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedSmallInteger('ministry_id');
            $table->text('decision_of_cabinet')->nullable();
            $table->text('decision_of_committee')->nullable();
            $table->unsignedSmallInteger('committee_id')->nullable();

            $table->foreign('committee_id','fk_agenda_histories_committee_id')->references('id')->on('ec_committees')->onDelete('cascade');
            $table->foreign('ec_meeting_request_id','fk_agenda_histories_ec_meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('agenda_id','fk_agenda_histories_agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            $table->foreign('step_id','fk_agenda_histories_step_id')->references('id')->on('mst_steps')->onDelete('cascade');
            $table->foreign('ministry_id','fk_agenda_histories_ministry_id')->references('id')->on('ec_ministry')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('agendas', function (Blueprint $table) {
            $table->unsignedInteger('fiscal_year_id')->nullable();

            $table->foreign('fiscal_year_id','fk_agendas_fiscal_year_id')->references('id')->on('mst_fiscal_years')->onDelete('cascade');
        });
        Schema::create('transfered_agendas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('committee_id');
            $table->unsignedSmallInteger('agenda_id');
            $table->unsignedBigInteger('agenda_history_id');
            $table->unsignedInteger('meeting_request_id')->nullable();
            $table->string('decision_details',500)->nullable();
            $table->boolean('is_hold')->nullable()->default(false);
            $table->unsignedSmallInteger('ministry_id');

            $table->foreign('committee_id','fk_transfered_agendas_committee_id')->references('id')->on('ec_committees')->onDelete('cascade');
            $table->foreign('agenda_id','fk_transfered_agendas_agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            $table->foreign('agenda_history_id','fk_transfered_agendas_agenda_history_id')->references('id')->on('agenda_histories')->onDelete('cascade');
            $table->foreign('meeting_request_id','fk_transfered_agendas_meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('ministry_id','fk_transfered_agendas_ministry_id')->references('id')->on('ec_ministry')->onDelete('cascade');

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });
        Schema::table('agenda_histories', function (Blueprint $table) {
            $table->unsignedInteger('transfered_agenda_id')->nullable();

            $table->foreign('transfered_agenda_id','fk_agenda_histories_transfered_agenda_id')->references('id')->on('transfered_agendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_histories');
    }
}
