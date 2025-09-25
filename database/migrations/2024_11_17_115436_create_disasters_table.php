<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disasters', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');
            $table->string('name');
            $table->string('event_type');
            $table->string('poisoning_specify')->nullable();
            $table->string('massaction_specify')->nullable();
            $table->string('accident_specify')->nullable();
            $table->string('other_specify')->nullable();
            
            $table->dateTime('occurence_date');
            $table->foreignId('city_id')->constrained('edcs_cities')->onDelete('cascade');
            $table->text('description')->nullable();
            
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('status')->default('ACTIVE'); //ACTIVE, DONE

            $table->text('hash');
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disasters');
    }
}
