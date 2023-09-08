<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcMeetingMinuteDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_meeting_minute_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('meeting_request_id')->nullable();
            $table->unsignedSmallInteger('committee_id')->nullable();
            $table->unsignedSmallInteger('fiscal_year_id')->nullable();
            $table->text('meeting_content')->nullable();
            $table->json('meeting_decisions')->nullable();
            $table->boolean('is_verified')->nullable()->default(0);
            $table->text('verified_date_bs')->nullable();
            $table->date('verified_date_ad')->nullable();
            $table->string('file_upload')->nullable();
            $table->boolean('is_mailed')->nullable()->default(0);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('meeting_request_id','fk_ec_meeting_minute_details_meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('fiscal_year_id','fk_ec_meeting_minute_details_fiscal_year_id')->references('id')->on('mst_fiscal_years')->onDelete('cascade');
            $table->foreign('committee_id','fk_ec_meeting_minute_details_committee_id')->references('id')->on('ec_committees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
