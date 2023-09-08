<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAgendaTableThreeLevelUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendas', function (Blueprint $table) {

            // Ministry 2nd level user
            $table->boolean('is_second_level_user_approve')->default(false);
            $table->boolean('is_second_level_user_rejection')->default(false);
            $table->string('second_level_rejection_remarks')->nullable();

            // Ministry 3rd level user
            $table->boolean('is_third_level_user_approve')->default(false);
            $table->boolean('is_third_level_user_rejection')->default(false);
            // rejection_remarks already exists

            // Cabinet 1st level user
            $table->boolean('is_cabinet_first_level_user_approve')->default(false);
            $table->boolean('is_cabinet_first_level_user_rejection')->default(false);

            // Cabinet 2nd level user
            $table->boolean('is_cabinet_second_user_approve')->default(false);
            $table->boolean('is_cabinet_second_level_user_rejection')->default(false);

            // Cabinet 3rd level user
            // 'is_approved',
            // 'is_rejected', These fileds are already exists

            // only for Ministry 1st level user (Role has Creator => Agenda)
            $table->string('paramarsha_and_others')->nullable();
            $table->string('agenda_reason_and_ministry_sipharis')->nullable();
            $table->string('decision_reason')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendas', function (Blueprint $table) {
            //
        });
    }
}
