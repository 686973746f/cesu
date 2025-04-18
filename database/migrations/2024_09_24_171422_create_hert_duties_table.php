<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHertDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hert_duties', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->text('description')->nullable();

            $table->date('event_date')->nullable();
            $table->string('status'); //OPEN, PENDING, COMPLETED
            $table->string('code');
            $table->integer('cycle_number')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('hert_duties');
    }
}
