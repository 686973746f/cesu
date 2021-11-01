<?php

namespace App\Models;

use App\Models\MonitoringSheetSub;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringSheetMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'forms_id',
        'region',
        'ccname',
        'date_lastexposure',
        'date_endquarantine',
        'magicURL',
    ];

    public function forms() {
        return $this->belongsTo(Forms::class);
    }

    public function ifnosx($date, $mer) {
        $subdata = MonitoringSheetSub::where('monitoring_sheet_masters_id', $this->id)
        ->whereDate('forDate', $date)
        ->where('forMeridian', $mer)
        ->first();

        if($subdata) {
            if(is_null($subdata->fever) 
            && $subdata->fever == 0 
            && $subdata->cough == 0 
            && $subdata->sorethroat == 0 
            && $subdata->colds == 0 
            && $subdata->fever == 0 
            && is_null($subdata->os1) 
            && is_null($subdata->os2) 
            && is_null($subdata->os3)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getos($date, $mer) {
        $subdata = MonitoringSheetSub::where('monitoring_sheet_masters_id', $this->id)
        ->whereDate('forDate', $date)
        ->where('forMeridian', $mer)
        ->first();

        if($subdata) {
            if(!is_null($subdata->os1) || !is_null($subdata->os2) || !is_null($subdata->os3)) {
                $osarr = [];

                if(!is_null($subdata->os1)) {
                    array_push($osarr, $subdata->os1);
                }
                if(!is_null($subdata->os2)) {
                    array_push($osarr, $subdata->os2);
                }
                if(!is_null($subdata->os3)) {
                    array_push($osarr, $subdata->os3);
                }
                
                return implode(",", $osarr);
            }
            else {
                return NULL;
            }
        }
    }
}
