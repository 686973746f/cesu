<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisSystemPopulation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'brgy_id',
        'year',
        'population_m',
        'population_f',
        'population_estimate_total',
        'population_actual_total',
        'household_estimate_total',
        'household_actual_total',

        'END_POP_FIL',
        'END_POP_MAL',
        'END_POP_SCH',
        'POP_UNDER1M',
        'POP_UNDER1F',
        'POP_0_6MOSM',
        'POP_0_6MOSF',
        'POP_0_59MOSM',
        'POP_0_59MOSF',
        'POP_6MOSM',
        'POP_6MOSF',
        'POP_6_11MOSM',
        'POP_6_11MOSF',
        'POP_12_23MOSM',
        'POP_12_23MOSF',
        'POP_12_59MOSM',
        'POP_12_59MOSF',
        'POP_0_1YRM',
        'POP_0_1YRF',
        'POP_0_14YRM',
        'POP_0_14YRF',
        'POP_1YRM',
        'POP_1YRF',
        'POP_2YRM',
        'POP_2YRF',
        'POP_2YRABOVEM',
        'POP_2YRABOVEF',
        'POP_3YRM',
        'POP_3YRF',
        'POP_4YRM',
        'POP_4YRF',
        'POP_1_4M',
        'POP_1_4F',
        'POP_5_9M',
        'POP_5_9F',
        'POP_5_65YRM',
        'POP_5_65YRF',
        'POP_5YRABOVEM',
        'POP_5YRABOVEF',
        'POP_6YRM',
        'POP_6YRF',
        'POP_9_14YRM',
        'POP_9_14YRF',
        'POP_10_14YRM',
        'POP_10_14YRF',
        'POP_10_19YRM',
        'POP_10_19YRF',
        'POP_10_49YRM',
        'POP_10_49YRF',
        'POP_12YRM',
        'POP_12YRF',
        'POP_15_19YRM',
        'POP_15_19YRF',
        'POP_15_49YRM',
        'POP_15_49YRF',
        'POP_20_49YRM',
        'POP_20_49YRF',
        'POP_20_59YRM',
        'POP_20_59YRF',
        'POP_20YRABOVEM',
        'POP_20YRABOVEF',
        'POP_25YRABOVEM',
        'POP_25YRABOVEF',
        'POP_60_65YRM',
        'POP_60_65YRF',
        'POP_60YRABOVEM',
        'POP_60YRABOVEF',
    ];

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'brgy_id');
    }
}
