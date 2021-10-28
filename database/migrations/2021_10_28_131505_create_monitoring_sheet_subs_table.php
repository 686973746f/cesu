<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringSheetSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_sheet_subs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('monitoring_sheet_masters_id')->constrained()->onDelete('cascade');
            $table-date('forDate');
            $table->text('forMeridian');
            $table->double('fever');
            $table->tinyInt('cough');
            $table->tinyInt('sorethroat');
            $table->tinyInt('dob');
            $table->tinyInt('colds');
            $table->tinyInt('diarrhea');
            $table->text('os1')->nullable();
            $table->text('os2')->nullable();
            $table->text('os3')->nullable();
            $table->text('magicURL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring_sheet_subs');
    }
}
