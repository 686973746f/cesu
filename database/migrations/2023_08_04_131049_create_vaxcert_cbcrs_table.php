<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaxcertCbcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaxcert_cbcrs', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->text('name');
            $table->text('type');
            $table->text('region');
            $table->text('province');
            $table->text('lgu');
            $table->text('barangay');
            $table->text('street_address')->nullable();
            $table->text('building')->nullable();
            $table->text('ownership_classification');
            $table->text('vaccine_teams');
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
        Schema::dropIfExists('vaxcert_cbcrs');
    }
}
