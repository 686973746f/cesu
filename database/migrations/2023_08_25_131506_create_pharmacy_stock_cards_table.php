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
            //$table->foreignId('subsupply_id')->constrained('pharmacy_supply_subs')->onDelete('cascade'); // Should be NULLABLE

            $table->foreignId('stock_id')->nullable()->constrained('pharmacy_supply_sub_stocks')->onDelete('cascade');
            $table->string('status')->default('APPROVED'); //PENDING, APPROVED
            $table->string('type'); //RECEIVED, ISSUED, REVERSAL, ADJUSTMENT
            $table->foreignId('reversed_stock_card_id')
            ->nullable()
            ->constrained('pharmacy_stock_cards')
            ->nullOnDelete();

            //$table->integer('before_qty_box')->nullable();
            $table->integer('before_qty_piece')->nullable();
            $table->integer('qty_to_process');
            $table->string('qty_type')->default('PIECE');
            //$table->integer('after_qty_box')->nullable();
            $table->integer('after_qty_piece')->nullable();
            $table->double('total_cost')->nullable();
            $table->text('drsi_number')->nullable();
            
            $table->foreignId('receiving_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('received_from_stc_id')
            ->nullable()
            ->constrained('pharmacy_stock_cards')
            ->nullOnDelete();
            $table->foreignId('receiving_patient_id')->nullable()->constrained('pharmacy_patients')->onDelete('cascade');
            $table->foreignId('patient_prescription_id')->nullable()->constrained('pharmacy_prescriptions')->onDelete('cascade');
            $table->integer('patient_age_years')->nullable();
            $table->text('recipient')->nullable();
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('sentby_branch_id')->nullable()->constrained('pharmacy_branches')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('processed_at')->nullable();

            $table->string('rx_fromfacility')->nullable();
            $table->string('rx_fromdoctor')->nullable();
            $table->string('rx_fromdoctor_licenseno')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')
                  ->nullable()
                  ->after('id');

            $table->unique('request_uuid', 'uq_pharmacy_stock_cards_request_uuid');
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
