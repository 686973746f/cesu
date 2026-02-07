<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseFpVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_fp_visits', function (Blueprint $table) {
            $table->id();
            $table->char('enabled', 1)->default('Y');
            $table->foreignId('fp_tcl_id')->constrained('inhouse_family_plannings')->onDelete('cascade');
            $table->string('client_type');

            $table->string('method_used');
            $table->date('visit_date_estimated')->nullable();
            $table->date('visit_date_actual')->nullable();
            $table->string('status')->nullable(); //PENDING, DONE, DROP-OUT
            $table->char('is_permanent', 1)->default('N');
            $table->char('is_visible', 1)->default('Y');

            $table->date('dropout_date')->nullable();
            $table->string('dropout_reason')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->uuid('request_uuid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_fp_visits');
    }
}
