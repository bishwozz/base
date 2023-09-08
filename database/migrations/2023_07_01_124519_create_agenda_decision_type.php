<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaDecisionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_decision_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('agenda_decision_code');
            $table->string('agenda_decision_content');
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        Schema::table('agenda_histories', function (Blueprint $table) {
            $table->unsignedSmallInteger('agenda_decision_type_id')->nullable();
            $table->foreign('agenda_decision_type_id','fk_agenda_histories_agenda_decision_type_id')->references('id')->on('agenda_decision_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_decision_type');
    }
}
