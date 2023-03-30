<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcVaccineBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_vaccine_brands', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('generic_name')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
        });

        Schema::table('abtc_vaccine_brands', function (Blueprint $table) {
            $table->decimal('price_per_dose')->after('generic_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abtc_vaccine_brands');
    }
}
