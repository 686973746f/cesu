<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacySuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_branch_id')->constrained('pharmacy_branches')->onDelete('cascade');
            $table->text('name');
            $table->string('category');

            $table->text('sku_code')->nullable();
            $table->text('po_contract_number')->nullable();
            $table->text('supplier')->nullable();
            $table->text('description')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage_strength')->nullable();
            $table->string('unit_measure')->nullable();
            $table->text('entity_name')->nullable();
            $table->string('source_of_funds')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('mode_of_procurement')->nullable();
            $table->string('end_user')->nullable();

            $table->string('expiration_month')->nullable();
            $table->string('expiration_year')->nullable();

            $table->integer('config_piecePerStock');
            $table->integer('box_currentStock');
            $table->integer('piece_currentStock');
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
        Schema::dropIfExists('pharmacy_supplies');
    }
}
