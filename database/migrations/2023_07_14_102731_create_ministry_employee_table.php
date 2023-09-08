<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinistryEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_ministry_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('ministry_id');
            $table->string('full_name',100);
            $table->unsignedSmallInteger('post_id');
            $table->string('phone_number',10)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('date_from_bs',10)->nullable();
            $table->date('date_from_ad')->nullable();
            $table->string('date_to_bs',10)->nullable();
            $table->date('date_to_ad')->nullable();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('post_id','fk_ec_ministry_employees_post_id')->references('id')->on('mst_posts');
            $table->foreign('ministry_id','fk_ec_ministry_employees_ministry_id')->references('id')->on('ec_ministry');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('employee_id')->nullable();

            $table->foreign('employee_id','fk_users_employee_id')->references('id')->on('ec_ministry_employees');
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
