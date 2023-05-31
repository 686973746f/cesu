<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinelistMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linelist_masters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('type');
            $table->string('dru');
            $table->string('laSallePhysician')->nullable();
            $table->dateTime('laSalleDateAndTimeShipment')->nullable();
            $table->string('email')->nullable();
            $table->string('contactPerson');
            $table->string('contactTelephone')->nullable();
            $table->string('contactMobile');
            $table->string('laSallePreparedBy')->nullable();
            $table->dateTime('laSallePreparedByDate')->nullable();
            $table->tinyInteger('is_override')->default(0);

            $table->time('time_started')->nullable();
            $table->date('date_started')->nullable();
            $table->tinyInteger('is_locked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linelist_master');
    }
}
