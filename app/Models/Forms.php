<?php

namespace App\Models;

//use App\Models\Records;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forms extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function records() {
        return $this->belongsTo(Records::class);
    }

    public function getEditedBy() {
        if(!is_null($this->updated_by)) {
            $u = User::find($this->updated_by);
            return $u->name;
        }
        else {
            return NULL;
        }
    }

    public function getType() {
        if($this->pType == 'PROBABLE') {
            return 'SUSPECTED';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'CLOSE CONTACT';
        }
        else if($this->pType == 'TESTING') {
            return 'NON-COVID CASE';
        }
        else {
            return 'N/A';
        }
    }

    public function getTestNum() {
        if(!is_null($this->testDateCollected2)) {
            return 2;
        }
        else {
            return 1;
        }
    }

    public function ifScheduled() {
        if(!is_null($this->testDateCollected2) || !is_null($this->testDateCollected1)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getLatestTestDate() {
        if(!is_null($this->testDateCollected2)) {
            return date('m/d/Y', strtotime($this->testDateCollected2));
        }
        else {
            return date('m/d/Y', strtotime($this->testDateCollected1));
        }
    }

    public function getLatestTestType() {
        if(!is_null($this->testDateCollected2)) {
            return $this->testType2;
        }
        else {
            return $this->testType1;
        }
    }

    public function getLatestTestResult() {
        if(!is_null($this->testDateCollected2)) {
            return $this->testResult2;
        }
        else {
            return $this->testResult1;
        }
    }

    public function getLatestTestDateReleased() {
        if(!is_null($this->testDateReleased2)) {
            return date('m/d/Y', strtotime($this->testDateReleased2));
        }
        else if(!is_null($this->testDateReleased1)) {
            return date('m/d/Y', strtotime($this->testDateReleased1));
        }
        else {
            return NULL;
        }
    }

    public function getLatestTestLaboratory() {
        if(!is_null($this->testLaboratory2)) {
            return $this->testLaboratory2;
        }
        else if(!is_null($this->testLaboratory1)) {
            return $this->testLaboratory1;
        }
        else {
            return NULL;
        }
    }

    public function getAttendedOnSwab() {
        if(!is_null($this->isPresentOnSwabDay)) {
            if($this->isPresentOnSwabDay == 1) {
                return 'YES';
            }
            else {
                return 'NO';
            }
        }
        else {
            return 'PENDING';
        }
    }

    public function getReferralCode() {
        if(!is_null($this->majikCode)) {
            $check = PaSwabDetails::where('majikCode', $this->majikCode)->first();
            if($check) {
                return $check->linkCode;
            }
            else {
                return 'N/A';
            }
        }
        else {
            return 'N/A';
        }
    }

    public function displayTestType() {
        if(!is_null($this->testDateCollected2)) {
            if($this->testType2 == 'OPS' || $this->testType2 == 'NPS' || $this->testType2 == 'OPS AND NPS') {
                if($this->testType2 == 'OPS AND NPS') {
                    return 'RT-PCR (OPS+NPS)';
                }
                else {
                    return 'RT-PCR ('.$this->testType2.')';
                }
            }
            else if($this->testType2) {
                return 'ANTIGEN';
            }
            else {
                return 'OTHERS';
            }
        }
        else {
            if($this->testType1 == 'OPS' || $this->testType1 == 'NPS' || $this->testType1 == 'OPS AND NPS') {
                if($this->testType1 == 'OPS AND NPS') {
                    return 'RT-PCR (OPS+NPS)';
                }
                else {
                    return 'RT-PCR ('.$this->testType1.')';
                }
            }
            else if($this->testType1) {
                return 'ANTIGEN';
            }
            else {
                return 'OTHERS';
            }
        }
    }

    public function getQuarantineStatus() {
        if($this->dispoType == 1) {
            return 'ADMITTED AT HOSPITAL';
        }
        else if($this->dispoType == 2) {
            return 'ADMITTED AT OTHER ISOLATION FACILITY';
        }
        else if($this->dispoType == 3) {
            return 'HOME QUARANTINE';
        }
        else if($this->dispoType == 4) {
            return 'DISCHARGED TO HOME';
        }
        else if($this->dispoType == 5) {
            return 'OTHERS';
        }
        else if($this->dispoType == 6) {
            return 'ADMITTED AT GENERAL TRIAS ISOLATION FACILITY';
        }
        else if($this->dispoType == 6) {
            return 'ADMITTED AT GENERAL TRIAS ISOLATION FACILITY #2';
        }
        else {
            return 'UNKNOWN';
        }
    }

    public function ifCaseFinished() {
        if($this->outcomeCondition == 'Recovered' || $this->outcomeCondition == 'Died' || $this->caseClassification == 'Non-COVID-19 Case') {
            return true;
        }
        else {
            return false;
        }
    }

    public function ifEligibleToUpdate() {
        if($this->ifCaseFinished()) {
            if(auth()->user()->ifTopAdmin()) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if($this->caseClassification == 'Confirmed') {
                if(auth()->user()->ifTopAdmin()) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return true;
            }
        }
    }

    public function ifInFacilityOne() {
        if($this->status == 'approved' && $this->caseClassification == 'Confirmed' && $this->outcomeCondition == 'Active' && $this->dispoType == 6) {
            return true;
        }
        else {
            return false;
        }
    }

    public function ifOldCif() {
        //get latest cif
        $form = Forms::where('records_id', $this->records_id)->orderBy('created_at', 'DESC')->first();

        if($this->id == $form->id) {
            return false;
        }
        else {
            return true;
        }
    }

    public function getOldCif() {
        $form = Forms::where('id', '!=', $this->id)->where('records_id', $this->records_id)->orderBy('created_at', 'DESC')->get();

        return $form;
    }

    public function getNewCif() {
        $form = Forms::where('records_id', $this->records_id)->orderBy('created_at', 'DESC')->first();

        if($form) {
            return $form->id;
        }
        else {
            return false;
        }
    }

    public function getContactTracingList() {
        if(!is_null($this->ccid_list)) {
            $exploded = explode(",", $this->ccid_list);

            return Forms::whereIn('id', $exploded)->get();
        }
        else {
            return false;
        }
    }

    public function exposureHistory() {
        return $this->hasMany(ExposureHistory::class);
    }

    public function getOutcomeDate() {
        if($this->outcomeCondition == 'Recovered') {
            return date('m/d/Y', strtotime($this->outcomeRecovDate));
        }
        else if ($this->outcomeCondition == 'Died') {
            return date('m/d/Y', strtotime($this->outcomeDeathDate));
        }
        else {
            return NULL;
        }
    }

    public function getPossibleRecoveryDate() {
        if($this->caseClassification == 'Confirmed' && $this->outcomeCondition != 'Died') {
            $dateToday = Carbon::parse(date('Y-m-d'));

            if($this->dispositionType != 6 && $this->dispositionType != 7) {
                if(!is_null($this->testType2)) {
                    $swabDateCollected = $this->testDateCollected2;
                }
                else {
                    $swabDateCollected = $this->testDateCollected1;
                }

                if($this->dispositionType == 1 || $this->healthStatus == 'Severe' || $this->healthStatus == 'Critical') {
                    //$daysToRecover = 21;
                    $daysToRecover = 10;
                }
                else {
                    if(!is_null($this->records->vaccinationDate2)) {
                        $date1 = Carbon::parse($this->records->vaccinationDate2);
                        $days_diff = $date1->diffInDays($dateToday);

                        if($days_diff >= 14) {
                            //$daysToRecover = 7;
                            $daysToRecover = 5;
                        }
                        else {
                            //$daysToRecover = 10;
                            $daysToRecover = 5;
                        }
                    }
                    else {
                        if($this->records->vaccinationName1 == 'JANSSEN') {
                            $date1 = Carbon::parse($this->records->vaccinationDate1);
                            $days_diff = $date1->diffInDays($dateToday);

                            if($days_diff >= 14) {
                                //$daysToRecover = 7;
                                $daysToRecover = 5;
                            }
                            else {
                                //$daysToRecover = 10;
                                $daysToRecover = 5;
                            }
                        }
                        else {
                            //$daysToRecover = 10;
                            $daysToRecover = 5;
                        }
                    }
                }

                return Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('m/d/Y (D)');
            }
            else {
                return 'N/A';
            }
        }
        else {
            return 'N/A';
        }
    }

    public function getTravelHistory() {
        $arr = array();
        if($this->expoitem2 == 1) {
            if(!is_null($this->placevisited)) {
                if(!is_null($this->locName1)) {
                    array_push($arr, mb_strtoupper($this->locName1).', '.mb_strtoupper($this->locAddress1).' ('.date('m/d/Y', strtotime($this->locDateFrom1)).'-'.date('m/d/Y', strtotime($this->locDateTo1)).')');
                }
    
                if(!is_null($this->locName2)) {
                    array_push($arr, mb_strtoupper($this->locName2).', '.mb_strtoupper($this->locAddress2).' ('.date('m/d/Y', strtotime($this->locDateFrom2)).'-'.date('m/d/Y', strtotime($this->locDateTo2)).')');
                }
    
                if(!is_null($this->locName3)) {
                    array_push($arr, mb_strtoupper($this->locName3).', '.mb_strtoupper($this->locAddress3).' ('.date('m/d/Y', strtotime($this->locDateFrom3)).'-'.date('m/d/Y', strtotime($this->locDateTo3)).')');
                }
    
                if(!is_null($this->locName4)) {
                    array_push($arr, mb_strtoupper($this->locName4).', '.mb_strtoupper($this->locAddress4).' ('.date('m/d/Y', strtotime($this->locDateFrom4)).'-'.date('m/d/Y', strtotime($this->locDateTo4)).')');
                }
    
                if(!is_null($this->locName5)) {
                    array_push($arr, mb_strtoupper($this->locName5).', '.mb_strtoupper($this->locAddress5).' ('.date('m/d/Y', strtotime($this->locDateFrom5)).'-'.date('m/d/Y', strtotime($this->locDateTo5)).')');
                }
    
                if(!is_null($this->locName6)) {
                    array_push($arr, mb_strtoupper($this->locName6).', '.mb_strtoupper($this->locAddress6).' ('.date('m/d/Y', strtotime($this->locDateFrom6)).'-'.date('m/d/Y', strtotime($this->locDateTo6)).')');
                }
    
                return implode(" | ", $arr);
            }
            else {
                return 'N/A';
            }
        }
        else {
            return 'N/A';
        }
    }

    public function getSubgroup() {
        if($this->testingCat != 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19') {
            return $this->testingCat;
        }
        else {
            return 'ALL';
        }
    }

    public function getTkcAdmittedType() {
        if($this->dispoType == 1) {
            return 'hospital';
        }
        else if($this->dispoType == 6 || $this->dispoType == 7) {
            return 'facility';
        }
        else if($this->dispoType == 2) {
            return 'facility';
        }
        else if($this->dispoType == 3 || $this->dispoType == 4) {
            return 'home';
        }
        else {
            return 'others';
        }
    }
    
    public function getTkcTreatmentFacility() {
        if($this->dispoType == 1 || $this->dispoType == 2) {
            return $this->dispoName;
        }
        else if($this->dispoType == 6) {
            return 'GENERAL TRIAS ISOLATION FACILITY, BRGY SANTIAGO';
        }
        else if($this->dispoType == 7) {
            return 'GENERAL TRIAS ISOLATION FACILITY, BRGY JAVALERA';
        }
        else if($this->dispoType == 3 || $this->dispoType == 4) {
            return '';
        }
        else {
            return $this->dispoName;
        }
    }

    public function getTkcQuarantineStatus() {
        if($this->outcomeCondition == 'Active') {
            return 'Ongoing';
        }
        else if($this->outcomeCondition == 'Recovered') {
            return 'Completed';
        }
        else {
            return 'Ongoing';
        }
    }

    public function getTkcVerAssessment() {
        if($this->caseClassification == 'Suspect') {
            return 'SC';
        }
        else if($this->caseClassification == 'Confirmed') {
            return 'CC';
        }
        else if($this->caseClassification == 'Probable') {
            return 'PC';
        }
        else if($this->caseClassification == 'Non-COVID-19 Case') {
            return 'NCA';
        }
    }

    public function getTkcOutcome() {
        if($this->outcomeCondition == 'Active') {
            return 'Improving';
        }
        else if($this->outcomeCondition == 'Recovered') {
            return 'Recovered';
        }
        else if($this->outcomeCondition == 'Died') {
            return 'Died';
        }
    }

    public function getTkcLabInfoType() {
        $txt_string = '';
        
        if($this->testType1 == 'OPS AND NPS' || $this->testType1 == 'OPS' || $this->testType1 == 'NPS') {
            $txt_string = 'RTPCR';
        }
        else if($this->testType1 == 'ANTIGEN') {
            $txt_string = 'ANTIGEN';
        }
        else {
            $txt_string = 'OTHERS';
        }

        /*
        if($this->testType1 == 'OPS AND NPS') {
            $txt_string = 'OPS & NPS';
        }
        else {
            $txt_string = $this->testType1;
        }
        */

        if(!is_null($this->testDateCollected2)) {
            if($this->testType2 == 'OPS AND NPS' || $this->testType2 == 'OPS' || $this->testType2 == 'NPS') {
                $txt_string = $txt_string.',RTPCR';
            }
            else if($this->testType2 == 'ANTIGEN') {
                $txt_string = $txt_string.',ANTIGEN';
            }
            else {
                $txt_string = $txt_string.',OTHERS';
            }

            /*
            if($this->testType2 == 'OPS AND NPS') {
                $txt_string = $txt_string.',OPS & NPS';
            }
            else {
                $txt_string = $txt_string.','.$this->testType2;
            }
            */
        }

        return $txt_string;
    }

    public function getTkcLab() {
        $txt_string = $this->testLaboratory1;

        if(!is_null($this->testDateCollected2)) {
            $txt_string = $txt_string.','.$this->testLaboratory2;
        }

        return $txt_string;
    }

    public function getTkcOtherTest() {
        $txt_string = '';

        if($this->testType1 == 'OTHERS') {
            $txt_string = $this->testTypeOtherRemarks1;
        }

        if(!is_null($this->testDateCollected2) && $this->testType2 == 'OTHERS') {
            $txt_string = $txt_string.','.$this->testTypeOtherRemarks2;
        }

        return $txt_string;
    }

    public function getTkcLabResult() {
        $txt_string = ucwords(strtolower($this->testResult1));

        if(!is_null($this->testDateCollected2)) {
            $txt_string = $txt_string.','.ucwords(strtolower($this->testResult2));
        }

        return $txt_string;
    }

    public function getTkcDateCollected() {
        $txt_string = date('Y-m-d', strtotime($this->testDateCollected1));

        if(!is_null($this->testDateCollected2)) {
            $txt_string = $txt_string.','.date('Y-m-d', strtotime($this->testDateCollected2));
        }

        return $txt_string;
    }

    public function getTkcDateResult() {
        if(!is_null($this->testDateReleased1)) {
            $txt_string = date('m/d/Y', strtotime($this->testDateReleased1));
        }
        else {
            $txt_string = '';
        }

        if(!is_null($this->testDateCollected2)) {
            if(!is_null($this->testDateReleased2)) {
                $txt_string = $txt_string.','.date('Y-m-d', strtotime($this->testDateReleased2));
            }
            else {
                $txt_string = $txt_string.',';
            }
        }
        
        return $txt_string;
    }

    public function getTkcAntigenKit() {
        if($this->testType1 == 'ANTIGEN') {
            //get antigen code
            $g = Antigen::find($this->antigen_id1);

            $txt_string = $g->tkc_code;
        }
        else {
            $txt_string = '';
        }

        if(!is_null($this->testDateCollected2) && $this->testType2 == 'ANTIGEN') {
            $g = Antigen::find($this->antigen_id2);

            $txt_string = $txt_string.','.$g->tkc_code;
        }
        
        return $txt_string;
    }

    public function getTkcVaccinationCode($code) {
        if($code == 'BHARAT BIOTECH') {
            return 'Bharat BioTech';
        }
        else if($code == 'GAMALEYA SPUTNIK V') {
            return 'Sputnik V';
        }
        else if($code == 'JANSSEN') {
            return 'Janssen COVID-19 vaccine';
        }
        else if($code == 'MODERNA') {
            return 'COVID-19 vaccine Moderna';
        }
        else if($code == 'NOVARAX' || $code == 'NOVAVAX') {
            return 'Novavax';
        }
        else if($code == 'OXFORD ASTRAZENECA') {
            return 'COVID-19 vaccine AstraZeneca';
        }
        else if($code == 'PFIZER BIONTECH') {
            return 'Comirnaty';
        }
        else if($code == 'SINOPHARM') {
            return 'COVID-19 vaccine inact (Vero) HB02';
        }
        else if($code == 'SINOVAC CORONAVAC') {
            return 'CoronaVac';
        }
    }

    public function getTkcVaccinationName() {
        if(is_null($this->records->vaccinationDate1)) {
            $txt_string = '';
        }
        else {
            $txt_string = $this->getTkcVaccinationCode($this->records->vaccinationName1);

            if(!is_null($this->records->vaccinationDate2)) {
                $txt_string = $txt_string.'::'.$this->getTkcVaccinationCode($this->records->vaccinationName2);
            }

            if(!is_null($this->records->vaccinationDate3)) {
                $txt_string = $txt_string.'::'.$this->getTkcVaccinationCode($this->records->vaccinationName3);
            }

            if(!is_null($this->records->vaccinationDate4)) {
                $txt_string = $txt_string.'::'.$this->getTkcVaccinationCode($this->records->vaccinationName4);
            }
        }

        return $txt_string;
    }

    public function getTkcVaccinationDate() {
        if(is_null($this->records->vaccinationDate1)) {
            $txt_string = '';
        }
        else {
            $txt_string = $this->records->vaccinationDate1;

            if(!is_null($this->records->vaccinationDate2)) {
                $txt_string = $txt_string.'::'.$this->records->vaccinationDate2;
            }

            if(!is_null($this->records->vaccinationDate3)) {
                $txt_string = $txt_string.'::'.$this->records->vaccinationDate3;
            }

            if(!is_null($this->records->vaccinationDate4)) {
                $txt_string = $txt_string.'::'.$this->records->vaccinationDate4;
            }
        }

        return $txt_string;
    }

    public function getTkcVaccineNumberOfDose($type) {
        if(is_null($this->records->vaccinationDate1)) {
            $txt_string = '';
        }
        else {
            if($type == 'N') {
                $txt_string = '1';

                if(!is_null($this->records->vaccinationDate2)) {
                    $txt_string = $txt_string.'::2';
                }

                if(!is_null($this->records->vaccinationDate3)) {
                    $txt_string = $txt_string.'::3';
                }

                if(!is_null($this->records->vaccinationDate4)) {
                    $txt_string = $txt_string.'::4';
                }
            }
            else if($type == 'R') {
                $txt_string = '1st';

                if(!is_null($this->records->vaccinationDate2)) {
                    $txt_string = $txt_string.'::2nd';
                }

                if(!is_null($this->records->vaccinationDate3)) {
                    $txt_string = $txt_string.'::3rd';
                }

                if(!is_null($this->records->vaccinationDate4)) {
                    $txt_string = $txt_string.'::4th';
                }
            }
        }

        return $txt_string;
    }

    public function getTkcInvestigationAction() {
        if($this->caseClassification == 'Confirmed') {
            return 'TEST_CONFIRMED';
        }
        else if(!is_null($this->testDateCollected1) || !is_null($this->testDateCollected2)) {
            if($this->isPresentOnSwabDay == 1) {
                return 'TEST_INITIATED';
            }
            else {
                return 'TEST_SCHEDULED';
            }
        }
        else if($this->dispoType == 1) {
            return 'FOR_HOSPITAL';
        }
        else {
            return 'TRACE_INFO';
        }
    }

    public function getTkcNatureOfExposure() {
        $arr_search = explode(",", $this->placevisited);
        /*
        Health Facility,Closed Settings,School,Workplace,Market,Social Gathering,Others,Transport Service
        */

        $final_list = [];
        if(in_array('Health Facility', $arr_search)) {
            $final_list[] = 'HF';
        }

        if(in_array('Closed Settings', $arr_search)) {
            $final_list[] = 'HH';
        }

        if(in_array('School', $arr_search)) {
            $final_list[] = 'SCH';
        }

        if(in_array('Workplace', $arr_search)) {
            $final_list[] = 'WS';
        }

        if(in_array('Market', $arr_search)) {
            $final_list[] = 'M';
        }

        if(in_array('Social Gathering', $arr_search)) {
            $final_list[] = 'E';
        }

        if(in_array('Others', $arr_search)) {
            $final_list[] = 'O';
        }

        if(in_array('Transport Service', $arr_search)) {
            $final_list[] = 'LV';
        }

        return implode(",", $final_list);
    }

    public function getTkcHasExposure() {
        if($this->expoitem2 == 0) {
            return 'UNAVAILABLE';
        }
        else if($this->expoitem2 == 3) {
            return 'UNKNOWN';
        }
        else {
            return 'KNOWN';
        }
    }
}
