<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvacuationCenterRespondersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_center_responders', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('evacuation_center_id')->constrained('evacuation_centers')->onDelete('cascade');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('nickname')->nullable();
            $table->string('sex', 1);
            $table->date('bdate');

            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();

            $table->string('position');
            $table->string('office');
            $table->string('bls_trained', 1)->default('N');

            $table->dateTime('duty_started')->nullable();
            $table->dateTime('duty_end')->nullable();

            $table->string('status');
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('evacuation_center_responders');
    }
}
