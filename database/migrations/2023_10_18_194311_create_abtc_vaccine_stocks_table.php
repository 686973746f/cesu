<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcVaccineStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_vaccine_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_id')->constrained('abtc_vaccine_brands')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('abtc_vaccination_sites')->onDelete('cascade');
            
            $table->integer('initial_stock');
            $table->date('initial_date');
            $table->integer('current_stock');
            $table->integer('patient_dosecount_init')->default(0);

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('abtc_vaccine_stocks');
    }
}
