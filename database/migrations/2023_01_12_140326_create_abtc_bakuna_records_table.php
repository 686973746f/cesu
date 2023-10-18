<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbtcBakunaRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abtc_bakuna_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('abtc_patients')->onDelete('cascade');
            $table->foreignId('vaccination_site_id')->constrained('abtc_vaccination_sites')->onDelete('cascade');
            $table->text('case_id');
            $table->tinyInteger('is_booster')->default(0);
            $table->tinyInteger('is_preexp')->default(0);
            $table->date('case_date')->nullable();
            $table->text('case_location')->nullable();
            $table->string('animal_type')->nullable();
            $table->string('animal_type_others')->nullable();
            $table->tinyInteger('if_animal_vaccinated')->default(0);
            $table->date('bite_date')->nullable();
            $table->string('bite_type')->nullable();
            $table->string('body_site')->nullable();
            $table->tinyInteger('category_level');
            $table->tinyInteger('washing_of_bite');
            $table->date('rig_date_given')->nullable();
            $table->string('pep_route');
            $table->text('brand_name')->nullable();
            $table->date('d0_date');
            $table->tinyInteger('d0_done')->default(0);
            $table->tinyInteger('d0_vaccinated_inbranch')->nullable();
            $table->text('d0_brand')->nullable();
            $table->date('d3_date');
            $table->tinyInteger('d3_done')->default(0);
            $table->tinyInteger('d3_vaccinated_inbranch')->nullable();
            $table->text('d3_brand')->nullable();
            $table->date('d7_date');
            $table->tinyInteger('d7_done')->default(0);
            $table->tinyInteger('d7_vaccinated_inbranch')->nullable();
            $table->text('d7_brand')->nullable();
            $table->date('d14_date');
            $table->tinyInteger('d14_done')->default(0);
            $table->tinyInteger('d14_vaccinated_inbranch')->nullable();
            $table->text('d14_brand')->nullable();
            $table->date('d28_date');
            $table->tinyInteger('d28_done')->default(0);
            $table->tinyInteger('d28_vaccinated_inbranch')->nullable();
            $table->text('d28_brand')->nullable();
            $table->string('outcome');
            $table->date('date_died')->nullable();
            $table->string('biting_animal_status');
            $table->date('animal_died_date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('abtc_bakuna_records');
    }
}
