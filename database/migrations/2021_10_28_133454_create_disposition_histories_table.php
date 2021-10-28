<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposition_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('forms_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('dispoType');
            $table->string('dispoName');
            $table->dateTime('dispoDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposition_histories');
    }
}
