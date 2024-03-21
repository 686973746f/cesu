<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SevereAcuteRespiratoryInfection extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public function getFullName() {
        $getFullName = $this->last_name.', '.$this->first_name;

        if(!is_null($this->middle_name)) {
            $getFullName = $getFullName.' '.$this->middle_name;
        }

        if(!is_null($this->suffix_name)) {
            $getFullName = $getFullName.' '.$this->suffix_name;
        }

        return $getFullName;
    }
}
