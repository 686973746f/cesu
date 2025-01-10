<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlsMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bls_mains', function (Blueprint $table) {
            $table->id();

            $table->string('batch_number');
            $table->string('batch_name');
            $table->string('is_refresher', 1)->default('N');
            $table->string('agency');
            $table->string('training_date_start');
            $table->string('training_date_end')->nullable();
            $table->string('venue')->nullable();

            $table->text('instructors_list')->nullable();
            $table->string('prepared_by')->nullable();

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
        Schema::dropIfExists('bls_mains');
    }
}
