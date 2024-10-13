<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdcsCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edcs_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('edcs_provinces')->onDelete('cascade');
            $table->string('edcs_code');
            $table->string('name');
            $table->string('alt_name')->nullable();
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
        Schema::dropIfExists('edcs_cities');
    }
}
