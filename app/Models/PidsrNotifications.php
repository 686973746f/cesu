<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PidsrNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'disease',
        'disease_id',
        'message',
        'viewedby_id',
    ];

    public function ifRead() {
        $arr = explode(',', $this->viewedby_id);

        if(in_array(auth()->user()->id, $arr)) {
            return true;
        }
        else {
            return false;
        }
    }
}
