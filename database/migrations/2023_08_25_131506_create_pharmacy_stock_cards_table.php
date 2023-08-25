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
            $table->string('type');
            $table->integer('before_qty');
            $table->integer('qty_to_process');
            $table->integer('after_qty');
            $table->double('total_cost')->nullable();
            $table->text('drsi_number')->nullable();
            
            $table->text('recipient')->nullable();
            $table->text('remarks')->nullable();
            
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
        Schema::dropIfExists('pharmacy_stock_cards');
    }
}
