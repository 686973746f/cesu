<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptanceLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceptance_letters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex');
            
            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');
            $table->text('address_houseno');

            $table->string('travelto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acceptance_letters');
    }
}
