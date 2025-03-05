<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDengueClusteringSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dengue_clustering_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('morbidity_week');
            $table->foreignId('brgy_id')->constrained('edcs_brgies')->onDelete('cascade');
            $table->string('purok_subdivision');
            $table->string('assigned_team')->nullable();
            $table->string('status')->default('PENDING'); //PENDING, CYCLE1, CYCLE2, CYCLE3
            $table->dateTime('cycle1_date')->nullable();
            $table->dateTime('cycle2_date')->nullable();
            $table->dateTime('cycle3_date')->nullable();
            $table->dateTime('cycle4_date')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('dengue_clustering_schedules');
    }
}
