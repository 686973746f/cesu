<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExposureHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exposure_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('set_date');
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->foreignId('cif_linkid')->nullable()->constrained('forms')->onDelete('cascade');
            $table->date('exposure_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exposure_histories');
    }
}