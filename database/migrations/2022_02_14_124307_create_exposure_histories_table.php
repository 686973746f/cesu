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
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('is_primarycc')->default(0);
            $table->tinyInteger('is_secondarycc')->default(0);
            $table->tinyInteger('is_tertiarycc')->default(0);
            $table->date('is_primarycc_date')->nullable();
            $table->date('is_secondarycc_date')->nullable();
            $table->date('is_tertiarycc_date')->nullable();
            $table->dateTime('is_primarycc_date_set')->nullable();
            $table->dateTime('is_secondarycc_date_set')->nullable();
            $table->dateTime('is_tertiarycc_date_set')->nullable();
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
