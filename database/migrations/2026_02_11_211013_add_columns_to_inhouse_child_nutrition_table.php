<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInhouseChildNutritionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inhouse_child_nutrition', function (Blueprint $table) {
            $table->date('nutrition2_date')->nullable();
            $table->double('length_atnutrition2')->nullable();
            $table->double('weight_atnutrition2')->nullable();
            $table->string('weight_status_atnutrition2')->nullable();

            $table->char('exclusive_breastfeeding1', 1)->nullable();
            $table->char('exclusive_breastfeeding2', 1)->nullable();
            $table->char('exclusive_breastfeeding3', 1)->nullable();

            $table->date('nutrition3_date')->nullable();
            $table->double('length_atnutrition3')->nullable();
            $table->double('weight_atnutrition3')->nullable();
            $table->string('weight_status_atnutrition3')->nullable();

            $table->char('exclusive_breastfeeding_4', 1)->nullable();
            $table->char('complementary_feeding', 1)->nullable();
            $table->char('cf_type', 1)->nullable();

            $table->date('nutrition4_date')->nullable();
            $table->double('length_atnutrition4')->nullable();
            $table->double('weight_atnutrition4')->nullable();
            $table->string('weight_status_atnutrition4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inhouse_child_nutrition', function (Blueprint $table) {
            $table->dropColumn([
                'nutrition2_date',
                'length_atnutrition2',
                'weight_atnutrition2',
                'weight_status_atnutrition2',

                'exclusive_breastfeeding1',
                'exclusive_breastfeeding2',
                'exclusive_breastfeeding3',

                'nutrition3_date',
                'length_atnutrition3',
                'weight_atnutrition3',
                'weight_status_atnutrition3',

                'exclusive_breastfeeding_4',
                'complementary_feeding',
                'cf_type',

                'nutrition4_date',
                'length_atnutrition4',
                'weight_atnutrition4',
                'weight_status_atnutrition4',
            ]);
        });
    }
}
