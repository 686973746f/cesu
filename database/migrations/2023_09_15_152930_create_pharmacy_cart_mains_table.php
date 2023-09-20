<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyCartMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_cart_mains', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('PENDING');
            $table->foreignId('patient_id')->nullable()->constrained('pharmacy_patients')->onDelete('cascade');
            $table->foreignId('prescription_id')->nullable()->constrained('pharmacy_prescriptions')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            
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
        Schema::dropIfExists('pharmacy_cart_mains');
    }
}
