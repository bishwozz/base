<?php

use Database\Seeders\MenuSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForCollege extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menus', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',200);
            $table->unsignedInteger('display_order')->nullable();
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->string('link', 255)->nullable();
            $table->unsignedSmallInteger('parent_id')->nullable();

            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
            
            $table->foreign('parent_id','fk_menus_parent_id')->references('id')->on('menus')->onDelete('cascade')->change();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name',100)->nullable();
            $table->unsignedSmallInteger('menu_id')->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('menu_id','fk_categories_menu_id')->references('id')->on('menus')->onDelete('cascade')->change();
        });
        
        Schema::create('about_us', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',255);
            $table->text('details');
            $table->string('file_upload',500)->nullable();
                    
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });


        Schema::create('galleries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',200);
            $table->text('description')->nullable();
            $table->unsignedInteger('display_order')->nullable();
            $table->string('display_image',500)->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();
            $table->text('gallery_images')->nullable();

            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('category_id','fk_galleries_category_id')->references('id')->on('categories')->onDelete('cascade')->change();

        });
       

        Schema::create('contact_us', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('full_name',100);
            $table->string('phone',50);
            $table->string('email',50);
            $table->string('message',500)->nullable();
                    
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });


        Schema::create('faqs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',100);
            $table->text('description')->nullable();
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


        Schema::create('mst_social_media', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',50)->nullable();
            $table->string('name',50)->nullable();
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
        





        Schema::create('blogs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',100)->nullable();
            $table->text('description',500)->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('visit_counts')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();
            $table->string('author')->nullable();
            $table->string('author_image')->nullable();

            $table->unsignedInteger('display_order')->nullable();
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('category_id','fk_blogs_category_id')->references('id')->on('categories');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',100)->nullable();
            $table->unsignedSmallInteger('sub_menu_id')->nullable();
            $table->string('description',500)->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->string('slug',50);
            $table->string('file_upload')->nullable();
            $table->string('external_redirect_url')->nullable();
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('sub_menu_id','fk_pages_sub_menu_id')->references('id')->on('menus');
        });

        // Schema::create('sayings', function (Blueprint $table) {
        //     $table->smallIncrements('id');
        //     $table->string('said_by',100)->nullable();
        //     $table->string('saying',500)->nullable();
        //     $table->string('image')->nullable();
        //     $table->unsignedSmallInteger('human_resource_id')->nullable();

        //     $table->unsignedInteger('display_order')->nullable();
        //     $table->timestamps();
        //     $table->unsignedInteger('created_by')->nullable();
        //     $table->unsignedInteger('updated_by')->nullable();
        //     $table->softDeletes();
        //     $table->unsignedSmallInteger('deleted_by')->nullable();
        //     $table->boolean('is_deleted')->nullable();
        //     $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

        //     $table->foreign('human_resource_id','fk_sayings_human_resource_id')->references('id')->on('human_resources');
        // });

        Schema::create('product', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',200);
            $table->string('file_upload',500)->nullable();
            $table->string('description',500)->nullable();
            $table->unsignedSmallInteger('display_order')->nullable();

            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
                    
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('full_name',100);
            $table->string('description',500)->nullable();
            $table->string('image')->nullable();
            $table->string('designation',50);
            $table->unsignedInteger('display_order')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        Schema::create('careers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title',100);
            $table->string('job_level',100);
            $table->text('company_benefits')->nullable();
            $table->string('image')->nullable();
            $table->integer('no_of_vacancies');
            $table->text('requirements');
            $table->text('skills');
            $table->string('location',50)->nullable();
            $table->text('summary')->nullable();
            $table->date('deadline')->nullable();
            $table->string('job_type',50)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        Schema::create('careers_applications', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone',10);
            $table->string('address');
            $table->text('message')->nullable();
            $table->string('cv_path');
            // $table->unsignedBigInteger('careers_id');
            $table->foreignId('careers_id')->references('id')->on('careers');

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
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
