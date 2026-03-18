<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInhouseChildNutritionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_child_nutrition', function (Blueprint $table) {
            $table->id();
            $table->char('enabled', 1)->default('Y');
            $table->foreignId('patient_id')->constrained('syndromic_patients')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('doh_facilities')->onDelete('cascade');
            $table->date('registration_date');

            $table->double('length_atbirth')->nullable();
            $table->double('weight_atbirth')->nullable();
            $table->string('weight_status')->nullable(); //L, N, U

            $table->date('breastfeeding')->nullable();
            $table->string('place_breastfeed')->nullable();

            $table->date('lb_iron1')->nullable();
            $table->date('lb_iron2')->nullable();
            $table->date('lb_iron3')->nullable();

            $table->date('vita1')->nullable();
            
            $table->date('vita2')->nullable();
            $table->date('vita3')->nullable();

            $table->date('vita4')->nullable();
            $table->date('vita5')->nullable();

            $table->date('vita6')->nullable();
            $table->date('vita7')->nullable();

            $table->date('vita8')->nullable();
            $table->date('vita9')->nullable();

            $table->date('mnp1')->nullable();
            $table->date('mnp1_completed')->nullable();
            $table->date('mnp2')->nullable();
            $table->date('mnp2_completed')->nullable();

            $table->date('lns1')->nullable();
            $table->date('lns1_completed')->nullable();
            $table->date('lns2')->nullable();
            $table->date('lns2_completed')->nullable();

            $table->char('mam_identified', 1)->nullable();
            $table->date('mam_dateidentified')->nullable();
            $table->char('enrolled_sfp', 1)->nullable();
            $table->char('mam_cured', 1)->nullable();
            $table->char('mam_noncured', 1)->nullable();
            $table->char('mam_defaulted', 1)->nullable();
            $table->char('mam_died', 1)->nullable();
            $table->date('mam_datedied')->nullable();

            $table->char('sam_identified', 1)->nullable();
            $table->date('sam_dateidentified')->nullable();
            $table->char('sam_complication', 1)->nullable();
            $table->char('sam_cured', 1)->nullable();
            $table->char('sam_noncured', 1)->nullable();
            $table->char('sam_defaulted', 1)->nullable();
            $table->char('sam_died', 1)->nullable();
            $table->date('sam_datedied')->nullable();

            $table->text('remarks')->nullable();
            $table->text('system_remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('request_uuid')->unique();
            
            $table->date('bdate_fixed')->nullable();
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();

            $table->char('is_locked')->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_child_nutrition');
    }
}
