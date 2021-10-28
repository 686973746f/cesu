<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringSheetMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_sheet_masters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('forms_id')->constrained()->onDelete('cascade');
            $table->text('region');
            $table->text('ccname')->nullable();
            $table->date('date_lastexposure')->nullable();
            $table->date('date_endquarantine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring_sheet_masters');
    }
}
