<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacySupplySubStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_supply_sub_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subsupply_id')->constrained('pharmacy_supply_subs')->onDelete('cascade');
            $table->date('expiration_date')->nullable();
            $table->text('batch_number')->nullable();
            $table->text('lot_number')->nullable();

            $table->integer('current_box_stock');
            $table->integer('current_piece_stock')->nullable();

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
        Schema::dropIfExists('pharmacy_supply_sub_stocks');
    }
}
