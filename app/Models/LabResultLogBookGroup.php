<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabResultLogBookGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'disease_tag',
        'title',
        'base_specimen_type',
        'base_test_type',
        'base_collector_name',
        'sent_to_ritm',
        'ritm_date_sent',
        'ritm_date_received',
        'ritm_received_by',
        'date_sent_others',
        'date_received_others',
        'facility_name_others',
        'driver_name',
        'case_open_date',
        'case_close_date',
        'case_closed_by',
        'is_finished',
        'remarks',
        'facility_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
