<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvacuationCenterFamilyHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evacuation_center_family_heads', function (Blueprint $table) {
            $table->id();
            $table->string('enabled', 1)->default('Y');

            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('nickname')->nullable();
            $table->string('sex', 1);
            $table->string('is_pregnant', 1)->default('N');
            $table->string('is_lactating', 1)->default('N');
            $table->date('bdate');
            $table->string('birthplace')->nullable();
            
            $table->string('cs');
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->string('mothermaiden_name')->nullable();
            $table->integer('monthlyfamily_income')->nullable();
            $table->string('is_pwd', 1)->default('N');
            $table->string('is_4ps', 1)->default('N');
            $table->string('is_indg', 1)->default('N');
            $table->string('indg_specify')->nullable();

            $table->string('id_presented')->nullable();
            $table->string('id_number')->nullable();
            $table->text('id_file')->nullable();
            $table->text('picture_file')->nullable();

            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_number2')->nullable();
            $table->string('philhealth_number')->nullable();

            $table->string('street_purok')->nullable();
            $table->foreignId('address_brgy_code')->constrained('edcs_brgies')->onDelete('cascade');
            //$table->string('is_headoffamily', 1)->default('N');
            //$table->integer('family_patient_id', 1)->nullable();
            //$table->string('relationship_tohead', 1)->nullable();
            
            $table->string('longlat')->nullable();
            $table->string('house_ownership');

            $table->string('cswd_serialno')->nullable();
            $table->string('dswd_serialno')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('hash');
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
        Schema::dropIfExists('evacuation_center_family_heads');
    }
}
