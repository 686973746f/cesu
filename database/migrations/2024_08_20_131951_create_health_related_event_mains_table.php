<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthRelatedEventMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_related_event_mains', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1);
            $table->string('event_name');
            $table->foreignId('facility_id')->nullable()->constrained('doh_facilities')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('qr');
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
        Schema::dropIfExists('health_related_event_mains');
    }
}
