<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdcsBrgiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edcs_brgies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('edcs_cities')->onDelete('cascade');
            $table->string('edcs_code');
            $table->string('name');
            $table->string('alt_name')->nullable();
            $table->string('brgyNameFhsis')->nullable();
            $table->integer('noncomm_customOrderNo')->nullable();
            $table->string('psgc_9digit')->nullable();
            $table->string('psgc_10digit')->nullable();
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
        Schema::dropIfExists('edcs_brgies');
    }
}
