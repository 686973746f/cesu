<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdControlNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opd_control_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->string('control_number');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('opd_control_numbers');
    }
}
