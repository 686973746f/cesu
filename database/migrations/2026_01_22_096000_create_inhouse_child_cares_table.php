<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseChildCaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_child_cares', function (Blueprint $table) {
            $table->id();

            $table->uuid('request_uuid')->unique();
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');

            $table->string('cpab1', 1)->nullable();
            $table->string('cpab2', 1)->nullable();
            $table->date('bcg1')->nullable();
            $table->date('bcg2')->nullable();
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
        Schema::dropIfExists('inhouse_child_cares');
    }
}
