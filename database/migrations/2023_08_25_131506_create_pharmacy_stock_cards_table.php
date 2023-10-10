<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subsupply_id')->constrained('pharmacy_supply_subs')->onDelete('cascade');
            $table->string('status')->default('approved');
            $table->string('type');
            $table->integer('before_qty_box')->nullable();
            $table->integer('before_qty_piece')->nullable();
            $table->integer('qty_to_process');
            $table->string('qty_type')->default('BOX');
            $table->integer('after_qty_box')->nullable();
            $table->integer('after_qty_piece')->nullable();
            $table->double('total_cost')->nullable();
            $table->text('drsi_number')->nullable();
            
            $table->foreignId('receiving_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('receiving_patient_id')->nullable()->constrained('pharmacy_patients')->onDelete('cascade');
            $table->foreignId('patient_prescription_id')->nullable()->constrained('pharmacy_prescriptions')->onDelete('cascade');
            $table->integer('patient_age_years')->nullable();
            $table->text('recipient')->nullable();
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('sentby_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('processed_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacy_stock_cards');
    }
}
