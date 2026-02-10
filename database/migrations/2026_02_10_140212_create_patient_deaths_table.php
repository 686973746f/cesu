<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_deaths', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->dateTime('death_date');
            $table->string('death_type')->nullable();
            $table->string('death_place')->nullable();
            $table->string('death_cause')->nullable();
            $table->string('remarks')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->uuid('request_uuid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_deaths');
    }
}
