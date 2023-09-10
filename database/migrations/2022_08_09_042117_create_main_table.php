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

        Schema::create('sliders', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',200);
            $table->string('file_upload',500)->nullable();
            $table->string('description',500)->nullable();
            $table->unsignedInteger('display_order')->nullable();

            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);
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
    }
}
