<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItrListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itr_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itrpatient_id')->constrained('itr_patients')->onDelete('cascade');
            $table->text('opdno');
            $table->datetime('consulation_date');
            $table->string('temperature');
            $table->string('bloodpressure');
            $table->string('weight');
            $table->string('respiratoryrate');
            $table->string('pulserate');
            $table->string('saturationperioxigen')->nullable();
            $table->tinyInteger('fever');
            $table->text('fever_remarks')->nullable();
            $table->tinyInteger('rash');
            $table->text('rash_remarks')->nullable();
            $table->tinyInteger('cough');
            $table->text('cough_remarks')->nullable();
            $table->tinyInteger('conjunctivitis');
            $table->text('conjunctivitis_remarks')->nullable();
            $table->tinyInteger('mouthsore');
            $table->text('mouthsore_remarks')->nullable();
            $table->tinyInteger('lossoftaste');
            $table->text('lossoftaste_remarks')->nullable();
            $table->tinyInteger('lossofsmell');
            $table->text('lossofsmell_remarks')->nullable();
            $table->tinyInteger('headache');
            $table->text('headache_remarks')->nullable();
            $table->tinyInteger('jointpain');
            $table->text('jointpain_remarks')->nullable();
            $table->tinyInteger('musclepain');
            $table->text('musclepain_remarks')->nullable();
            $table->tinyInteger('diarrhea');
            $table->text('diarrhea_remarks')->nullable();
            $table->tinyInteger('abdominalpain');
            $table->text('abdominalpain_remarks')->nullable();
            $table->tinyInteger('vomiting');
            $table->text('vomiting_remarks')->nullable();
            $table->tinyInteger('weaknessofextremities');
            $table->text('weaknessofextremities_remarks')->nullable();
            $table->tinyInteger('paralysis');
            $table->text('paralysis_remarks')->nullable();
            $table->tinyInteger('alteredmentalstatus');
            $table->text('alteredmentalstatus_remarks')->nullable();
            $table->tinyInteger('animalbite');
            $table->text('animalbite_remarks')->nullable();

            $table->text('bigmessage')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('itr_lists');
    }
}
