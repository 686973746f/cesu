<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskGeneratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_generators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('generate_every');
            $table->string('daily_except_days')->nullable();
            $table->string('weekly_whatday')->nullable();
            $table->string('monthly_whatday')->nullable();

            $table->string('has_duration', 1);
            $table->string('duration_type');
            $table->string('duration_daily_whattime')->nullable();
            $table->string('duration_weekly_howmanydays')->nullable();
            $table->string('duration_monthly_howmanymonth')->nullable();
            $table->string('duration_yearly_howmanyyear')->nullable();

            $table->string('encodedcount_enable', 1);
            $table->string('has_tosendimageproof', 1);
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
        Schema::dropIfExists('task_generators');
    }
}
