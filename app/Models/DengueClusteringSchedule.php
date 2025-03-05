<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DengueClusteringSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'morbidity_week',
        'brgy_id',
        'purok_subdivision',
        'assigned_team',
        'status',
        'cycle1_date',
        'cycle2_date',
        'cycle3_date',
        'cycle4_date',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'brgy_id');
    }

    public function getTotalPatients() {
        return Dengue::where('sys_clustering_schedule_id', $this->id)->count();
    }

    public function fetchClusteringList() {
        return Dengue::where('sys_clustering_schedule_id', $this->id)->get();
    }

    public function getStatus() {
        if($this->status == 'PENDING') {
            return 'PENDING';
        }
        else if($this->status == 'CYCLE1') {
            return '1ST CYCLE DONE';
        }
        else if($this->status == 'CYCLE2') {
            return '2ND CYCLE DONE';
        }
        else if($this->status == 'CYCLE3') {
            return '3RD CYCLE DONE';
        }
    }

    public function getUpcomingCycleDate() {
        if($this->status == 'PENDING') {
            if(is_null($this->cycle1_date)) {
                return 'N/A';
            }
            else {
                return date('M d, Y h:i A', strtotime($this->cycle1_date));
            }
        }
        else if($this->status == 'CYCLE1') {
            return date('M d, Y h:i A', strtotime($this->cycle2_date));
        }
        else if($this->status == 'CYCLE2') {
            return date('M d, Y h:i A', strtotime($this->cycle3_date));
        }
        else if($this->status == 'CYCLE3') {
            return '3RD CYCLE DONE';
        }
    }
}
