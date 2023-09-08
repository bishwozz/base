<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsForMeetingRequestNMinute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
            $table->unsignedInteger('agenda_id')->nullable()->change();
            $table->unsignedInteger('meeting_request_id')->nullable();
            $table->unsignedInteger('meeting_minute_id')->nullable();
            
            $table->foreign('meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('meeting_minute_id')->references('id')->on('ec_meeting_minute_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
}
