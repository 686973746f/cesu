<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDenguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dengues', function (Blueprint $table) {
            $table->string('Region');
            $table->text('Province');
            $table->text('Muncity');
            $table->text('Streetpurok');
            $table->date('DateOfEntry');
            $table->text('DRU');
            $table->text('PatientNum')->nullable();
            $table->string('FirstName');
            $table->string('FamilyName');
            $table->string('FullName');
            $table->integer('AgeYears');
            $table->integer('AgeMons');
            $table->integer('AgeDays');
            $table->string('Sex');
            $table->text('AddressOfDRU');
            $table->text('ProvOfDRU');
            $table->text('MuncityOfDRU');
            $table->date('DOB');
            $table->tinyInteger('Admitted');
            $table->date('DAdmit');
            $table->date('DOnset');
            $table->string('Type');
            $table->text('LabTest')->nullable();
            $table->text('LabRes')->nullable();
            $table->text('ClinClass')->nullable();
            $table->text('CaseClassification');
            $table->text('Outcome');
            $table->text('RegionOfDrU');
            $table->text('EPIID');
            $table->date('DateDied')->nullable();
            $table->text('lcd10Code')->nullable();
            $table->tinyInteger('MorbidityMonth');
            $table->tinyInteger('MorbidityWeek');
            $table->tinyInteger('AdmitToEntry');
            $table->tinyInteger('OnsetToAdmit');
            $table->tinyInteger('SentinelSite');
            $table->tinyInteger('DeleteRecord')->nullable();
            $table->string('Year')->nullable();
            $table->tinyInteger('Recstatus')->default(0);
            $table->integer('UniqueKey')->nullable();
            $table->text('NameOfDru');
            $table->text('ILHZ')->nullable();
            $table->text('District')->nullable();
            $table->text('Barangay')->nullable();
            $table->text('TYPEHOSPITALCLINIC')->nullable();
            $table->string('SENT')->default('N');
            $table->string('ip')->default('N');
            $table->string('ipgroup')->nullable();

            $table->string('MiddleName')->nullable();
            
            $table->id();
            $table->foreignId('records_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dengues');
    }
}
