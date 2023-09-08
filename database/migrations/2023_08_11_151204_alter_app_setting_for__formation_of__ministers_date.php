<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAppSettingForFormationOfMinistersDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            //
            $table->string('formation_of_council_ministers_date_bs', 10)->nullable();
        });
        DB::statement('ALTER TABLE agendas ALTER COLUMN file_upload TYPE VARCHAR(255) USING file_upload::VARCHAR');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            //
            $table->dropColumn('formation_of_council_ministers_date_bs');
        });
    }
}
