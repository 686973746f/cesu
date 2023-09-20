<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyQtyLimitPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_qty_limit_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->nullable()->constrained('pharmacy_prescriptions')->onDelete('cascade');
            $table->foreignId('master_supply_id')->nullable()->constrained('pharmacy_supply_masters')->onDelete('cascade');
            $table->integer('set_pieces_limit');
            $table->date('date_started');
            $table->date('date_finished')->nullable();
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
        Schema::dropIfExists('pharmacy_qty_limit_patients');
    }
}
