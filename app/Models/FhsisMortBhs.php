<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisMortBhs extends Model
{
    use HasFactory;

    protected $table = 'fhsis_MORT BHS';

    public $guarded = [];

    public function getCode() {
        $code = explode(";", $this->DISEASE);

        $s = Icd10Code::where('ICD10_CODE', $code[0])->first();

        return $s;
    }
}
