<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->string('name');
            $table->string('status')->default('ONGOING');
            $table->text('description');
            $table->string('oneDayEvent', 1)->default('Y');
            $table->date('date_start');
            $table->date('date_end')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('medical_events');
    }
}
