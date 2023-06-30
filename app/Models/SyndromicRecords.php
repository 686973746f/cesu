<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'opdno',
        'consulation_date',
        'temperature',
        'bloodpressure',
        'weight',
        'respiratoryrate',
        'pulserate',
        'saturationperioxigen',
        'fever',
        'fever_remarks',
        'rash',
        'rash_remarks',
        'cough',
        'cough_remarks',
        'colds',
        'colds_remarks',
        'conjunctivitis',
        'conjunctivitis_remarks',
        'mouthsore',
        'mouthsore_remarks',
        'lossoftaste',
        'lossoftaste_remarks',
        'lossofsmell',
        'lossofsmell_remarks',
        'headache',
        'headache_remarks',
        'jointpain',
        'jointpain_remarks',
        'musclepain',
        'musclepain_remarks',
        'diarrhea',
        'diarrhea_remarks',
        'abdominalpain',
        'abdominalpain_remarks',
        'vomiting',
        'vomiting_remarks',
        'weaknessofextremities',
        'weaknessofextremities_remarks',
        'paralysis',
        'paralysis_remarks',
        'alteredmentalstatus',
        'alteredmentalstatus_remarks',
        'animalbite',
        'animalbite_remarks',
        'bigmessage',
    ];
}
