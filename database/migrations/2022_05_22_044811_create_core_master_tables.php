<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreMasterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_fed_provinces', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);


            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_provinces_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_fed_provinces_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_fed_provinces_name_en');

        });

        Schema::create('mst_fed_districts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('province_id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_districts_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_fed_districts_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_fed_districts_name_en');
            $table->index('province_id','idx_mst_fed_districts_province_id');

            $table->foreign('province_id','fk_mst_fed_districts_province_id')->references('id')->on('mst_fed_provinces')->onDelete('cascade');

        });
        Schema::create('mst_fed_local_level_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_local_level_types_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_fed_local_level_types_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_fed_local_level_types_name_en');

        });
        Schema::create('mst_fed_local_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('district_id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->unsignedSmallInteger('level_type_id');
            $table->string('remarks',500)->nullable();
            $table->string('gps_lat',20)->nullable();
            $table->string('gps_long',20)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_local_levels_code');
            $table->foreign('district_id','fk_mst_fed_local_levels_district_id')->references('id')->on('mst_fed_districts')->onDelete('cascade');
            $table->foreign('level_type_id','fk_mst_fed_local_levels_level_type_id')->references('id')->on('mst_fed_local_level_types')->onDelete('cascade');

        });

        Schema::create('mst_nepali_months', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_nepali_months_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_nepali_months_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_nepali_months_name_en');

        });
        Schema::create('mst_fiscal_years', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('from_date_bs',10)->nullable();
            $table->date('from_date_ad')->nullable();
            $table->string('to_date_bs',10)->nullable();
            $table->date('to_date_ad')->nullable();
            $table->string('remarks',500)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fiscal_years_code');
            $table->unique(['from_date_bs','deleted_uq_code'],'uq_mst_fiscal_years_from_date_bs');
            $table->unique(['from_date_ad','deleted_uq_code'],'uq_mst_fiscal_years_from_date_ad');

        });

        Schema::create('mst_genders', function(Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name_en',200)->nullable();
            $table->string('name_lc',200);
            $table->string('remarks',500)->nullable();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_genders_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_mst_genders_name_lc');
            $table->unique(['name_en','deleted_uq_code'],'uq_mst_genders_name_en');
        });

        Schema::create('app_settings', function(Blueprint $table) {

            $table->smallIncrements('id');
            $table->string('code',200);
            $table->string('office_name_lc',200);
            $table->string('office_name_en',200)->nullable();
            $table->string('address_name_lc',200)->nullable();
            $table->string('address_name_en',200)->nullable();
            $table->string('letter_head_title_1',200)->nullable();
            $table->string('letter_head_title_2',200)->nullable();
            $table->string('letter_head_title_3',200)->nullable();
            $table->string('letter_head_title_4',200)->nullable();
            $table->unsignedSmallInteger('fiscal_year_id');
            $table->string('phone',10)->nullable();
            $table->string('fax',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('remarks',1000)->nullable();
            $table->integer('meeting_count')->default(0);
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_app_setting_code');
            $table->foreign('fiscal_year_id','fk_app_setting_fiscal_year_id')->references('id')->on('mst_fiscal_years')->onDelete('cascade');
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
