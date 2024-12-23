<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlsMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bls_mains', function (Blueprint $table) {
            $table->id();

            $table->string('batch_number');
            $table->string('agency');
            $table->string('training_date_start');
            $table->string('training_date_end');
            $table->string('venue')->nullable();

            $table->text('instructors_list')->nullable();
            $table->string('prepared_by')->nullable();
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
        Schema::dropIfExists('bls_mains');
    }
}
