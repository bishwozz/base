<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabinetMeetingRequestHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings_request_approval_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('meetings_request_id');
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

            $table->foreign('role_id','fk_meetings_request_approval_history_role_id')->references('id')->on('roles');
            $table->foreign('meetings_request_id','fk_meetings_request_approval_history_meetings_request_id')->references('id')->on('ec_meetings_requests');
        });
        Schema::create('meeting_minute_approval_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('meeting_minute_id');
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

            $table->foreign('role_id','fk_meeting_minute_approval_history_role_id')->references('id')->on('roles');
            $table->foreign('meeting_minute_id','fk_meeting_minute_approval_history_meeting_minute_id')->references('id')->on('ec_meeting_minute_details');
        });

        Schema::table('ec_meetings_requests', function (Blueprint $table) {
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('level_id')->default(1);

        });
        Schema::table('ec_meeting_minute_details', function (Blueprint $table) {
            //
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('cabinet_meeting_request_history');
    }
}
