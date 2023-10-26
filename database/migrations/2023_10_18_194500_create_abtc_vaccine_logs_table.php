<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcVaccineLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_vaccine_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_id')->constrained('abtc_vaccine_brands')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('abtc_vaccination_sites')->onDelete('cascade');

            $table->double('wastage_dose_count');
            $table->double('stocks_remaining');

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('abtc_vaccine_logs');
    }
}
