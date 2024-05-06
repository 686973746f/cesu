<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrgy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brgy', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained('city')->onDelete('cascade');
            $table->string('brgyName');
            $table->tinyInteger('displayInList')->default(1);
            $table->integer('estimatedPopulation')->nullable();
            $table->text('dilgCustCode')->nullable();
            $table->text('json_code')->nullable();
            $table->text('alt_name')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->text('edcs_pw')->nullable();
            $table->string('edcs_quicklogin_code', 10)->nullable();
            $table->dateTime('edcs_lastlogin_date')->nullable();
            $table->string('edcs_session_code', 5)->nullable();
            $table->ipAddress('edcs_ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brgy');
    }
}
