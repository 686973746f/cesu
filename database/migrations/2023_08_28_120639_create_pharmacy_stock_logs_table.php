<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyStockLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subsupply_id')->constrained('pharmacy_supply_subs')->onDelete('cascade');
            $table->string('type');
            $table->integer('get_stock_box')->nullable();
            $table->integer('get_stock_piece')->nullable();
            $table->integer('stock_credit_box')->nullable();
            $table->integer('stock_debit_box')->nullable();
            $table->integer('stock_credit_piece')->nullable();
            $table->integer('stock_debit_piece')->nullable();
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
        Schema::dropIfExists('pharmacy_stock_logs');
    }
}
