<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultipleFileUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('agenda_file_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

        Schema::create('multiple_agenda_files', function (Blueprint $table) {
            $table->id();
            $table->text('path')->nullable();
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('agenda_file_type_id')->nullable();
            $table->unsignedSmallInteger('agenda_id')->nullable();
            $table->foreign('agenda_file_type_id','fk_multiple_agenda_files_agenda_file_type_id')->references('id')->on('agenda_file_type')->onDelete('cascade');
            $table->foreign('agenda_id','fk_multiple_agenda_files_agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multiple_agenda_files');
    }
}
