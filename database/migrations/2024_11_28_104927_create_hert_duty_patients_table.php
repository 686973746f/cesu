<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHertDutyPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hert_duty_patients', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('event_id')->constrained('hert_duties')->onDelete('cascade');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();

            $table->integer('age_years');
            $table->string('sex', 1);

            $table->string('contact_number')->nullable();
            $table->string('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->nullable()->constrained('edcs_brgies')->onDelete('cascade');

            $table->text('chief_complaint');
            $table->string('actions_taken')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('hert_duty_patients');
    }
}
