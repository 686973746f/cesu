<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaSwabLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_swab_links', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('active')->default(1);
            $table->string('code');
            $table->string('secondary_code');
            $table->string('listOfAcceptedTestType')->nullable();
            $table->tinyInteger('enableLockAddress')->default(0);
            $table->string('lock_brgy')->nullable();
            $table->string('lock_city')->nullable();
            $table->string('lock_city_text')->nullable();
            $table->string('lock_province')->nullable();
            $table->string('lock_province_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa_swab_links');
    }
}
