<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamAssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fam_assists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_id')->nullable()->constrained('evacuation_center_families_insides')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('evacuation_center_family_members_insides')->onDelete('cascade');

            $table->text('assistance');
            $table->string('unit');
            $table->double('qty');
            $table->string('cost')->nullable();
            $table->string('provider');
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('fam_assists');
    }
}
