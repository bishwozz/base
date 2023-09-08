<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('pt_project', function (Blueprint $table) {
        //     $table->smallIncrements('id');
        //     $table->unsignedSmallInteger('fiscal_year_id');
        //     $table->unsignedSmallInteger('ministry_id');
        //     $table->string('project_name',200)->nullable();
        //     $table->string('project_code',20)->nullable();
        //     $table->string('expenditure_title',20)->nullable();
        //     $table->float('project_budget')->nullable();
        //     $table->string('from_date_bs',10)->nullable();
        //     $table->string('from_date_ad',10)->nullable();
        //     $table->date('to_date_bs',10)->nullable();
        //     $table->date('to_date_ad',10)->nullable();
        //     $table->string('comment',200)->nullable();
        //     $table->timestamps();
        //     $table->unsignedInteger('created_by')->nullable();
        //     $table->unsignedInteger('updated_by')->nullable();
        //     $table->softDeletes();
        //     $table->unsignedSmallInteger('deleted_by')->nullable();
        //     $table->boolean('is_deleted')->nullable();
        //     $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

        //     $table->foreign('fiscal_year_id','fk_pt_project_tracking_fiscal_year_id')->references('id')->on('mst_fiscal_years')->onDelete('cascade');
        //     $table->foreign('ministry_id','fk_pt_project_tracking_ministry_id')->references('id')->on('mst_ministries')->onDelete('cascade');
        // });


        // Schema::create('pt_project_milestones', function (Blueprint $table) {
        //     $table->smallIncrements('id');
        //     $table->string('name',100)->nullable();
        //     $table->unsignedSmallInteger('project_id');
        //     $table->float('milestone_score')->nullable();
        //     $table->string('description',300)->nullable();
        //     $table->date('to_date_bs',10)->nullable();
        //     $table->date('to_date_ad',10)->nullable();
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        //     $table->unsignedInteger('created_by')->nullable();
        //     $table->unsignedInteger('updated_by')->nullable();
        //     $table->softDeletes();
        //     $table->unsignedSmallInteger('deleted_by')->nullable();
        //     $table->boolean('is_deleted')->nullable();
        //     $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

        //     $table->foreign('project_id','fk_pt_project_milestones_project_id')->references('id')->on('pt_project')->onDelete('cascade');

        // });

        // Schema::create('mst_level', function (Blueprint $table) {
        //     $table->smallIncrements('id');
        //     $table->string('name_en',100)->nullable();
        //     $table->string('name_lc',100)->nullable();
        //     $table->string('description',300)->nullable();
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        //     $table->unsignedInteger('created_by')->nullable();
        //     $table->unsignedInteger('updated_by')->nullable();
        //     $table->softDeletes();
        //     $table->unsignedSmallInteger('deleted_by')->nullable();
        //     $table->boolean('is_deleted')->nullable();
        //     $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

        // });



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
