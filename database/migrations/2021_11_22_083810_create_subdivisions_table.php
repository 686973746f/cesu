<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubdivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdivisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brgy_id')->constrained('brgy')->onDelete('cascade');
            $table->tinyInteger('displayInList')->default(1);
            $table->text('subdName');
            $table->string('type')->nullable();
            $table->string('total_projectarea')->nullable();
            $table->string('total_lotsunits')->nullable();
            $table->integer('numof_population')->nullable();
            $table->integer('numof_household')->nullable();
            $table->text('dilgCustCode')->nullable();
            $table->text('gps_x')->nullable();
            $table->text('gps_y')->nullable();

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
        Schema::dropIfExists('subdivisions');
    }
}
