<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInventoryTransactionSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_inventory_transaction_subs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_transaction_id');
            $table->foreign('master_transaction_id', 'fk_master_transaction_id')
            ->references('id')
            ->on('property_inventory_transaction_mains')
            ->onDelete('cascade');
            
            $table->foreignId('stock_id')->constrained('property_inventory_stocks')->onDelete('cascade');
            $table->date('transaction_date');

            $table->integer('process_qty');
            $table->integer('before_qty')->nullable();
            $table->integer('after_qty')->nullable();
            
            $table->string('po_number')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('unit_price_amount')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('property_inventory_transaction_subs');
    }
}
