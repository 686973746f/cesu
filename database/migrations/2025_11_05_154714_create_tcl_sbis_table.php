<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTclSbisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tcl_sbis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('sbs_patients')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sbs_patients')->onDelete('cascade');
            $table->string('family_serialno')->nullable();

            $table->date('tt_diphtheria')->nullable();
            $table->date('mr_vaccine')->nullable();

            $table->date('f_hpv1')->nullable();
            $table->date('f_hpv2')->nullable();
            $table->date('f_hpv_datecompleted')->nullable();

            $table->text('remarks')->nullable();

            $table->text('consent_url')->nullable();
            $table->text('aefi_cif_url')->nullable();
            $table->text('form_url')->nullable();
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
        Schema::dropIfExists('tcl_sbis');
    }
}
