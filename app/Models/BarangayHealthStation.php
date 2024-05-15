<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayHealthStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'brgy_id',
        'name',
        'assigned_personnel_name',
        'assigned_personnel_position',
        'assigned_personnel_contact_number',
        'sys_code1',
        'sys_coordinate_x',
        'sys_coordinate_y',
    ];

    public function brgy() {
        return $this->belongsTo(Brgy::class, 'brgy_id');
    }
}
