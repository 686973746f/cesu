<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseContactHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_contact_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('forms_id')->constrained('forms')->onDelete('cascade');
            $table->foreignId('cc_on')->constrained('forms')->onDelete('cascade');
            $table->text('cc_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('close_contact_histories');
    }
}
