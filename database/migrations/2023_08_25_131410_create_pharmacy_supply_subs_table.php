<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacySupplySubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_supply_subs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_master_id')->constrained('pharmacy_supply_masters')->onDelete('cascade');
            $table->foreignId('pharmacy_branch_id')->constrained('pharmacy_branches')->onDelete('cascade');
            $table->text('self_sku_code')->nullable();
            $table->text('self_description')->nullable();

            $table->text('po_contract_number')->nullable();
            $table->text('supplier')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage_strength')->nullable();
            $table->string('unit_measure')->nullable();
            $table->text('entity_name')->nullable();
            $table->string('source_of_funds')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('mode_of_procurement')->nullable();
            $table->string('end_user')->nullable();

            $table->integer('default_issuance_per_box')->nullable();
            $table->integer('default_issuance_per_piece')->nullable();
            
            $table->integer('master_box_stock')->nullable();
            $table->integer('master_piece_stock')->nullable();

            $table->integer('self_maxbox_perduration')->nullable();
            $table->integer('self_maxpiece_perduration')->nullable();
            $table->integer('self_duration_days')->nullable();
            $table->integer('alert_percent')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('pharmacy_supply_subs');
    }
}
