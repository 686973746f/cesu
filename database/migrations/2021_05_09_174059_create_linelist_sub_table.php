<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinelistSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linelist_subs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('linelist_masters_id')->constrained('linelist_masters')->onDelete('cascade');
            $table->integer('specNo');
            $table->dateTime('dateAndTimeCollected');
            $table->string('accessionNo')->nullable();
            $table->foreignId('records_id')->constrained()->onDelete('cascade');
            $table->string('remarks');
            $table->string('oniSpecType')->nullable();
            $table->string('oniReferringHospital')->nullable();
            $table->tinyInteger('res_released')->default(0);
            $table->string('res_result')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linelist_sub');
    }
}
