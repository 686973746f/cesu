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
            $table->foreignId('vaccination_site_id')->nullable()->constrained('abtc_vaccination_sites')->onDelete('cascade');
            $table->text('case_id')->nullable();
            $table->tinyInteger('is_booster')->default(0);
            $table->tinyInteger('is_preexp')->default(0);
            $table->integer('queue_number')->nullable();
            $table->integer('priority_queue_number')->nullable();
            $table->integer('ff_queue_number')->nullable();
            $table->integer('ff_priority_queue_number')->nullable();
            $table->date('ff_queue_date')->nullable();
            $table->date('case_date')->nullable();
            $table->text('case_location')->nullable();
            $table->string('animal_type')->nullable();
            $table->string('animal_type_others')->nullable();
            $table->tinyInteger('if_animal_vaccinated')->default(0);
            $table->date('bite_date')->nullable();
            $table->string('bite_type')->nullable();
            $table->string('body_site')->nullable();
            $table->tinyInteger('category_level')->nullable();
            $table->tinyInteger('washing_of_bite');
            $table->date('rig_date_given')->nullable();
            $table->string('pep_route');
            $table->text('brand_name')->nullable();

            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            
            $table->date('d0_date')->nullable();
            $table->tinyInteger('d0_done')->default(0);
            $table->tinyInteger('d0_vaccinated_inbranch')->nullable();
            $table->text('d0_brand')->nullable();
            $table->foreignId('d0_done_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('d0_done_date')->nullable();

            $table->date('d3_date')->nullable();
            $table->tinyInteger('d3_done')->default(0);
            $table->tinyInteger('d3_vaccinated_inbranch')->nullable();
            $table->text('d3_brand')->nullable();
            $table->foreignId('d3_done_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('d3_done_date')->nullable();

            $table->date('d7_date')->nullable();
            $table->tinyInteger('d7_done')->default(0);
            $table->tinyInteger('d7_vaccinated_inbranch')->nullable();
            $table->text('d7_brand')->nullable();
            $table->foreignId('d7_done_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('d7_done_date')->nullable();

            $table->date('d14_date')->nullable();
            $table->tinyInteger('d14_done')->default(0);
            $table->tinyInteger('d14_vaccinated_inbranch')->nullable();
            $table->text('d14_brand')->nullable();
            $table->date('d28_date')->nullable();
            $table->foreignId('d14_done_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('d14_done_date')->nullable();

            $table->tinyInteger('d28_done')->default(0);
            $table->tinyInteger('d28_vaccinated_inbranch')->nullable();
            $table->text('d28_brand')->nullable();
            $table->foreignId('d28_done_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('d28_done_date')->nullable();

            $table->string('outcome');
            $table->date('date_died')->nullable();
            $table->string('biting_animal_status');
            $table->date('animal_died_date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('ics_ticketstatus')->default('OPEN');
            $table->foreignId('ics_grabbedby')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('ics_grabbed_date')->nullable();
            $table->foreignId('ics_finishedby')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('ics_finished_date')->nullable();

            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();
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
