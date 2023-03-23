<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaxcertProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaxcert_provinces', function (Blueprint $table) {
            $table->id();
            
            $table->string('sys_code');
            $table->string('code');
            $table->description('description');
            $table->description('province_code');
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
        Schema::dropIfExists('vaxcert_provinces');
    }
}
