<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInventoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('sub_id')->constrained('property_inventory_subs')->onDelete('cascade');
            $table->string('batch_serialno')->nullable();
            $table->text('adtn_referenceno')->nullable();
            $table->string('has_expiry', 1)->default('Y');
            $table->date('expiry_date')->nullable();
            $table->string('source'); //DOH, LGU, OTHERS
            $table->text('source_name')->nullable();

            $table->integer('current_qty')->default(0);
            
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
        Schema::dropIfExists('property_inventory_stocks');
    }
}
