<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolSectionPopulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_section_populations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('school_sections')->onDelete('cascade');

            $table->string('academic_year');
            $table->integer('count_male');
            $table->integer('count_female');
            $table->integer('count_total');

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
        Schema::dropIfExists('school_section_populations');
    }
}
