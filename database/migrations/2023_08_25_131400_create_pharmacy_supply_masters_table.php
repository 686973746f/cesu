<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacySupplyMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_supply_masters', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('sku_code');
            $table->text('sku_code_doh')->nullable();
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('quantity_type')->default('BOX');

            $table->integer('config_piecePerBox')->nullable();
            
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
        Schema::dropIfExists('pharmacy_supply_masters');
    }
}