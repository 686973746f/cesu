<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisasterStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disaster_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->foreignId('evacuation_center_id')->constrained('evacuation_centers')->onDelete('cascade');
            $table->dateTime('update_time');

            $table->integer('no_families');
            $table->integer('no_individuals');
            $table->integer('no_underfive');
            $table->integer('no_male');
            $table->integer('no_female');
            $table->integer('no_pregnant');
            $table->integer('no_lactating');
            $table->integer('no_senior');
            $table->integer('no_pwd');

            $table->integer('age_1');
            $table->integer('age_2');
            $table->integer('age_3');
            $table->integer('age_4');
            $table->integer('age_5');

            $table->integer('medicalneeds_age1');
            $table->integer('medicalneeds_age2');
            $table->integer('medicalneeds_age3');

            $table->string('has_electricity', 1)->default('Y');
            $table->string('has_water', 1)->default('Y');
            $table->string('has_communication', 1)->default('Y');
            $table->string('has_internet', 1)->default('Y');

            $table->string('rcho_functional', 1)->default('Y');
            $table->string('bhs_functional', 1)->default('Y');

            $table->string('has_flood', 1)->default('Y');
            $table->string('has_landslide', 1)->default('Y');
            $table->string('weather')->nullable();
            $table->string('roads_passable', 1)->default('Y');
            
            $table->string('email_sent', 1)->default('N');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('disaster_statuses');
    }
}
