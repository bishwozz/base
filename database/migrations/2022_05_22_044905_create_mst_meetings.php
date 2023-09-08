<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstMeetings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('mst_posts', function (Blueprint $table) {
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

            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_posts_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_posts_name_en');
            
        });
        Schema::create('mst_steps', function (Blueprint $table) {
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
            
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_steps_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_steps_name_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('mst_meetings');
        Schema::dropIfExists('mst_posts');
        Schema::dropIfExists('mst_steps');
    }
}
