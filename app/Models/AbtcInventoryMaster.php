<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventoryMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'name',
        'description',
        'uom',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }
}
