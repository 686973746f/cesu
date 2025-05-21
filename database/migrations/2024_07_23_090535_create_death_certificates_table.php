<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeathCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_certificates', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('if_fetaldeath');

            $table->string('lname')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('suffix')->nullable();
            $table->date('bdate')->nullable();
            $table->string('gender');

            $table->date('date_died')->nullable();
            $table->integer('age_death_years')->nullable();
            $table->integer('age_death_months')->nullable();
            $table->integer('age_death_days')->nullable();

            $table->date('fetald_dateofdelivery')->nullable();
            //$table->string('fetald_placeofdelivery')->nullable();
            $table->string('fetald_typeofdelivery')->nullable();
            $table->string('fetald_ifmultipledeliveries_fetuswas')->nullable();
            $table->string('fetald_methodofdelivery')->nullable();
            $table->string('fetald_methodofdelivery_others')->nullable();
            $table->string('fetald_birthorder')->nullable();
            $table->string('fetald_fetusweight')->nullable();
            $table->string('fetald_fetusdiedwhen')->nullable();
            $table->tinyInteger('fetald_lenghthpregnancyweeks')->nullable();

            $table->string('fetald_mother_lname')->nullable();
            $table->string('fetald_mother_fname')->nullable();
            $table->string('fetald_mother_mname')->nullable();

            $table->string('name_placeofdeath'); //Can be also used as place of Delivery
            $table->string('pod_insidecity', 1)->nullable();
            $table->text('pod_address_region_code')->nullable();
            $table->text('pod_address_region_text')->nullable();
            $table->text('pod_address_province_code')->nullable();
            $table->text('pod_address_province_text')->nullable();
            $table->text('pod_address_muncity_code')->nullable();
            $table->text('pod_address_muncity_text')->nullable();
            $table->text('pod_address_brgy_code')->nullable();
            $table->text('pod_address_brgy_text')->nullable();
            $table->text('pod_address_street')->nullable();
            $table->text('pod_address_houseno')->nullable();

            $table->text('address_region_code');
            $table->text('address_region_text');
            $table->text('address_province_code');
            $table->text('address_province_text');
            $table->text('address_muncity_code');
            $table->text('address_muncity_text');
            $table->text('address_brgy_code');
            $table->text('address_brgy_text');
            $table->text('address_street')->nullable();
            $table->text('address_houseno')->nullable();

            $table->string('maternal_condition')->default('N/A');

            $table->text('immediate_cause');
            $table->text('antecedent_cause')->nullable();
            $table->text('underlying_cause')->nullable();
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
        Schema::dropIfExists('death_certificates');
    }
}
