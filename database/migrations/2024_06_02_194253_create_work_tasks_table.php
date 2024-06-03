<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('has_duration', 1);
            $table->dateTime('until')->nullable();

            $table->string('status')->default('OPEN'); //OPEN, PENDING, FINISHED OR CLOSED
            $table->foreignId('grabbed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('grabbed_date')->nullable();
            $table->foreignId('transferred_to')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('transferred_date')->nullable();
            $table->foreignId('finished_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('finished_date')->nullable();
            $table->text('remarks')->nullable();

            $table->string('encodedcount_enable', 1);
            $table->integer('encodedcount')->nullable();
            $table->string('has_tosendimageproof', 1);
            $table->text('proof_image')->nullable();
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
        Schema::dropIfExists('work_tasks');
    }
}
