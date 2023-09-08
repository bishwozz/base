<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComitteeMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('mp_id');
            $table->unsignedSmallInteger('committee_id');
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

            $table->index('committee_id','idx_committee_members_committee_id');
            $table->index('mp_id','idx_committee_members_mp_id');
            $table->foreign('mp_id','fk_committee_members_mp_id')->references('id')->on('ec_mp');
            $table->foreign('committee_id','fk_committee_members_committee_id')->references('id')->on('ec_ministry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comittee_member');
    }
}
