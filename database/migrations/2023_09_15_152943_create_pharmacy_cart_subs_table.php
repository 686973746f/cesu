<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyCartSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy_cart_subs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_cart_id')->nullable()->constrained('pharmacy_cart_mains')->onDelete('cascade');
            $table->foreignId('subsupply_id')->nullable()->constrained('pharmacy_supply_subs')->onDelete('cascade');

            $table->integer('qty_to_process');
            $table->string('type_to_process');
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
        Schema::dropIfExists('pharmacy_cart_subs');
    }
}
