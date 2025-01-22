<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('APPROVED');
            $table->date('transaction_date');
            $table->foreignId('stock_id')->constrained('abtc_inventory_stocks')->onDelete('cascade');
            $table->string('type'); //ADD, SUB
            
            $table->integer('process_qty');
            $table->integer('before_qty')->nullable();
            $table->integer('after_qty')->nullable();
            
            $table->string('po_number')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('unit_price_amount')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('abtc_inventory_transactions');
    }
}
