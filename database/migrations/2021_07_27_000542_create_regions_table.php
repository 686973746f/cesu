<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('regionName');
            $table->text('json_code')->nullable();
            $table->text('alt_name')->nullable();
            $table->text('short_name1')->nullable();
            $table->text('short_name2')->nullable();
            $table->text('edcs_code')->nullable();
            $table->string('psgc_9digit')->nullable();
            $table->string('psgc_10digit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
