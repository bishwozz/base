<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('agenda_code',100);
            $table->string('agenda_title');
            $table->string('agenda_description')->nullable();
            $table->string('file_upload')->nullable();
            $table->boolean('is_submitted')->nullable()->default(false);
            $table->boolean('is_hold')->nullable()->default(false);
            $table->boolean('is_approved')->nullable()->default(false);
            $table->boolean('is_rejected')->nullable()->default(false);
            $table->string('rejection_remarks')->nullable();
            $table->unsignedSmallInteger('step_id')->nullable();
            $table->string('agenda_number',100)->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedSmallInteger('agenda_type_id');
            $table->unsignedSmallInteger('ministry_id');
            $table->unsignedInteger('ec_meeting_request_id')->nullable();

            $table->foreign('agenda_type_id','fk_agendas_agenda_type_id')->references('id')->on('mst_agenda_types')->onDelete('cascade');
            $table->foreign('ministry_id','fk_agendas_ministry_id')->references('id')->on('ec_ministry')->onDelete('cascade');
            $table->foreign('ec_meeting_request_id','fk_agendas_ec_meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('step_id','fk_agendas_step_id')->references('id')->on('mst_steps')->onDelete('cascade');

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_request');
    }
}

