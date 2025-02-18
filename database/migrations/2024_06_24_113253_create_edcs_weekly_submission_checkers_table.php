<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdcsWeeklySubmissionCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edcs_weekly_submission_checkers', function (Blueprint $table) {
            $table->id();
            $table->string('facility_name');
            $table->string('year');
            $table->string('week');
            $table->string('status'); //SUBMITTED, ZERO CASE, NO SUBMISSION
            $table->string('type'); //AUTO, MANUAL
            $table->string('waive_status')->nullable(); //LATE SUBMIT, LATE ZERO CASE
            $table->dateTime('waive_date')->nullable();
            
            $table->integer('abd_count')->nullable();
            $table->integer('afp_count')->nullable();
            $table->integer('ames_count')->nullable();
            $table->integer('hepa_count')->nullable();
            $table->integer('chikv_count')->nullable();
            $table->integer('cholera_count')->nullable();
            $table->integer('covid_count')->nullable();
            $table->integer('dengue_count')->nullable();
            $table->integer('diph_count')->nullable();
            $table->integer('hfmd_count')->nullable();
            $table->integer('ili_count')->nullable();
            $table->integer('lepto_count')->nullable();
            $table->integer('measles_count')->nullable();
            $table->integer('meningo_count')->nullable();
            $table->integer('mpox_count')->nullable();
            $table->integer('nt_count')->nullable();
            $table->integer('nnt_count')->nullable();
            $table->integer('pert_count')->nullable();
            $table->integer('rabies_count')->nullable();
            $table->integer('rota_count')->nullable();
            $table->integer('sari_count')->nullable();
            $table->integer('typhoid_count')->nullable();

            $table->text('excel_file')->nullable();
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
        Schema::dropIfExists('edcs_weekly_submission_checkers');
    }
}
