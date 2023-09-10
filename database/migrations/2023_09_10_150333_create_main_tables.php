<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainTables extends Migration
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

        Schema::create('games', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',200);
            $table->string('game_img',500)->nullable();
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

        Schema::create('app_settings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title')->nullable();
            $table->string('full_address',100)->nullable();
            $table->string('opening_time')->nullable();
            $table->string('closing_time')->nullable();
            $table->string('from_day')->nullable();
            $table->string('to_day')->nullable();
            $table->string('phone',10)->nullable();
            $table->string('contact_phone',10)->nullable();
            $table->string('discription',500)->nullable();
            $table->string('fax',50)->nullable();
            $table->string('gps',1000)->nullable();
            $table->string('email',50)->nullable();
            $table->string('logo',500)->nullable();
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        $DbSeed = new DatabaseSeeder();
        $DbSeed->run();
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
