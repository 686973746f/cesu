<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolPopulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_populations', function (Blueprint $table) {
            $table->id();

            $table->string('academic_year');
            $table->foreignId('section_id')->constrained('school_sections')->onDelete('cascade');
            $table->integer('pop_male');
            $table->integer('pop_female');

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
        Schema::dropIfExists('school_populations');
    }
}
