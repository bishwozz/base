<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryMasterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_ministry_member_type', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_ministry_member_type_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_ministry_member_type_name_en');

        });

        Schema::create('ec_ministry', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('remarks',500)->nullable();
            $table->integer('agenda_count')->default(0);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['name_lc','deleted_uq_code'],'uq_ec_ministry_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_ec_ministry_name_en');
            
        });

        Schema::create('ec_committees', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('remarks',500)->nullable();
            $table->unsignedInteger('meeting_count')->default(0);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['name_lc','deleted_uq_code'],'uq_ec_committees_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_ec_committees_name_en');
            
        });

        Schema::create('ec_mp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('gender_id');
            $table->unsignedSmallInteger('district_id')->nullable();
            $table->unsignedSmallInteger('post_id')->nullable();
            $table->text('photo_path',500)->nullable();
            $table->text('signature_path',500)->nullable();
            $table->string('mobile_number',10)->nullable();
            $table->string('email',200)->nullable();
            $table->smallInteger('display_order')->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('district_id','fk_ec_mp_district_id')->references('id')->on('mst_fed_districts');
            $table->foreign('gender_id','fk_ec_mp_gender_id')->references('id')->on('mst_genders');
            $table->foreign('post_id','fk_ec_mp_post_id')->references('id')->on('mst_posts');
        });

        Schema::create('ec_ministry_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('mp_id');
            $table->unsignedSmallInteger('ministry_id');
            // $table->unsignedSmallInteger('member_type_id');
            $table->string('date_from_bs',10)->nullable();
            $table->date('date_from_ad')->nullable();
            $table->string('date_to_bs',10)->nullable();
            $table->date('date_to_ad')->nullable();
            $table->string('remarks',500)->nullable();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->index('ministry_id','idx_ec_ministry_members_ministry_id');
            $table->index('mp_id','idx_ec_ministry_members_mp_id');
            $table->foreign('mp_id','fk_ec_ministry_members_mp_id')->references('id')->on('ec_mp');
            $table->foreign('ministry_id','fk_ec_ministry_members_ministry_id')->references('id')->on('ec_ministry');
            // $table->foreign('member_type_id','fk_ec_ministry_members_member_type_id')->references('id')->on('mst_ministry_member_type');
        });

        Schema::create('ec_political_parties', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
            
            $table->unique(['name_lc','deleted_uq_code'],'uq_ec_political_parties_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_ec_political_parties_name_en');
             
        });

        Schema::create('ec_mp_tenure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('mp_id');
            $table->unsignedSmallInteger('political_party_id')->nullable();
            $table->string('date_from_bs',10);
            $table->date('date_from_ad');
            $table->string('date_to_bs',10)->nullable();
            $table->date('date_to_ad')->nullable(); 
            $table->string('remarks',1000)->nullable();            
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('mp_id','fk_ec_mp_tenure_mp_id')->references('id')->on('ec_mp');
            $table->foreign('political_party_id','fk_ec_mp_tenure_political_party_id')->references('id')->on('ec_political_parties');
        });

        Schema::create('ec_meetings_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('fiscal_year_id')->nullable();
            $table->unsignedSmallInteger('committee_id')->nullable();
            $table->string('meeting_code',200);
            // $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('start_date_bs',10);
            $table->date('start_date_ad')->nullable();
            $table->time('start_time');
            $table->string('remarks',1000)->nullable();
            $table->tinyInteger('meeting_for');
            $table->boolean('is_mailed')->default(false);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('fiscal_year_id','fk_ec_meetings_requests_fiscal_year_id')->references('id')->on('mst_fiscal_years')->onDelete('cascade');
            $table->foreign('committee_id','fk_ec_meetings_requests_committee_id')->references('id')->on('ec_committees')->onDelete('cascade');
        });

     

        Schema::create('meeting_attendance_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('meeting_request_id')->nullable();
            $table->unsignedSmallInteger('mp_id')->nullable();
            $table->unsignedSmallInteger('ministry_id')->nullable();
            $table->string('requested_date_bs',10)->nullable();
            $table->date('requested_date_ad')->nullable();
            $table->string('remarks',1000)->nullable();
            $table->boolean('apply_for_meeting_attendance')->default(false);
            $table->boolean('is_present')->default(false);
            $table->boolean('is_mailed')->default(false);
            $table->time('present_time')->nullable();


            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('meeting_request_id','fk_meeting_attendance_details_meeting_request_id')->references('id')->on('ec_meetings_requests')->onDelete('cascade');
            $table->foreign('mp_id','fk_meeting_attendance_details_mp_id')->references('id')->on('ec_mp')->onDelete('cascade');
            $table->foreign('ministry_id','fk_meeting_attendance_details_ministry_id')->references('id')->on('ec_ministry')->onDelete('cascade');
            $table->unique(['mp_id','meeting_request_id','deleted_uq_code'],'uq_meeting_attendance_details_mp_id');


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
