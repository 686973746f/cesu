<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SevereAcuteRespiratoryInfection extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public function getFullName() {
        $getFullName = $this->lname.', '.$this->fname;

        if(!is_null($this->middle_name)) {
            $getFullName = $getFullName.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $getFullName = $getFullName.' '.$this->suffix;
        }

        return $getFullName;
    }

    public function getName() {
        $getFullName = $this->lname.', '.$this->fname;

        if(!is_null($this->middle_name)) {
            $getFullName = $getFullName.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $getFullName = $getFullName.' '.$this->suffix;
        }

        return $getFullName;
    }
}
