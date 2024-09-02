<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonkeyPoxLaboratoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monkey_pox_laboratories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_contact_id')->constrained('monkey_poxes')->onDelete('cascade');
            $table->string('enabled', 1);

            $table->string('test_name');
            $table->date('date_collected');
            $table->string('laboratory');
            $table->string('results');
            $table->date('date_released');
            
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
        Schema::dropIfExists('monkey_pox_laboratories');
    }
}
