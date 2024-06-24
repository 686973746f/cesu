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
            $table->string('status');
            $table->string('waive_status')->nullable();
            $table->dateTime('waive_date')->nullable();
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
