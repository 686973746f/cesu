<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInventorySubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_inventory_subs', function (Blueprint $table) {
            $table->id();

            $table->string('enabled', 1)->default('Y');
            $table->foreignId('master_id')->constrained('property_inventory_mains')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('abtc_vaccination_sites')->onDelete('cascade');

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
        Schema::dropIfExists('property_inventory_subs');
    }
}
