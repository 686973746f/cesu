<?php

namespace App\Http\Controllers;

use App\Models\Nt;
use Carbon\Carbon;
use App\Models\Abd;
use App\Models\Aes;
use App\Models\Afp;
use App\Models\Ahf;
use App\Models\Nnt;
use App\Models\Psp;
use App\Models\Aefi;
use App\Models\Ames;
use App\Models\Brgy;
use App\Models\Diph;
use App\Models\Hfmd;
use App\Models\Pert;
use App\Models\User;
use App\Models\Chikv;
use App\Models\Dengue;
use App\Models\Rabies;
use App\Models\Anthrax;
use App\Models\Cholera;
use App\Models\Malaria;
use App\Models\Measles;
use App\Models\Meningo;
use App\Models\Typhoid;
use App\Models\Hepatitis;
use App\Models\Icd10Code;
use App\Models\Influenza;
use App\Models\Rotavirus;
use App\Models\ExportJobs;
use App\Models\Meningitis;
use Illuminate\Support\Str;
use App\Models\MedicalEvent;
use Illuminate\Http\Request;
use App\Jobs\CallOpdErExport;
use App\Models\Employee;
use App\Models\Leptospirosis;
use App\Models\PharmacyCartSub;
use App\Models\PharmacyPatient;
use App\Models\SyndromicDoctor;
use function PHPSTORM_META\map;
use App\Models\SyndromicPatient;
use App\Models\SyndromicRecords;
use App\Models\PharmacySupplySub;
use Illuminate\Support\Facades\DB;
use App\Models\FhsisTbdotsMorbidity;
use App\Models\PharmacyPrescription;

use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use OpenSpout\Common\Entity\Style\Style;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Rap2hpoutre\FastExcel\SheetCollection;

class SyndromicController extends Controller
{
    public function index() {
        
        $plist = explode(",", auth()->user()->permission_list);

        /*
        if(in_array('GLOBAL_ADMIN', $plist) && !request()->input('opd_view') || in_array('ITR_BRGY_ADMIN', $plist) && !request()->input('opd_view') || in_array('ITR_BRGY_ENCODER', $plist) && !request()->input('opd_view')) {
            return redirect()->route('syndromic_home', [
                'opd_view' => 1,
            ]);
        }
        */

        $medicalevent_list = MedicalEvent::where('status', 'ONGOING')
        ->get();

        if(auth()->user()->isStaffSyndromic() && request()->input('opd_view')) {
            //STAFF ACCOUNT OPD VIEW
            if(request()->input('d')) {
                $sdate = request()->input('d');
            }
            else {
                $sdate = date('Y-m-d');
            }

            $ll = SyndromicRecords::whereDate('created_at', $sdate)
            ->orderBy('created_at', 'DESC')
            ->where('checkup_type', 'CHECKUP')
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('encodedfrom_tbdots', 0)
            ->paginate(10);

            $select_view = 'home';
        }
        else if(auth()->user()->isStaffSyndromic()) {
            //STAFF ACCOUNT BRGY VIEW
            if(request()->input('q')) {
                $q = request()->input('q');

                $base_search = SyndromicPatient::where(function ($qry) use ($q) {
                    $qry->where('id', $q)
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%");
                });

                if(auth()->user()->itr_facility_id == 11730) {
                    //Manggahan Search
                    $ll = (clone $base_search)->orWhere('facility_controlnumber', $q)->paginate(10);
                }
                else {
                    $ll = (clone $base_search)->paginate(10);
                }

                $select_view = 'search';
            }
            else {
                if(!(request()->input('showVerified'))) {
                    $ll = SyndromicRecords::where('brgy_verified', 0)
                    ->orderBy('created_at', 'ASC')
                    ->paginate(10);
                }
                else {
                    $ll = SyndromicRecords::where('brgy_verified', 1)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10);
                }

                $select_view = 'home';
            }
        }
        else if(auth()->user()->isTbdotsEncoder()) {
            //TB-DOTS ITR HOME
            if(request()->input('q')) {
                $q = request()->input('q');

                $ll = SyndromicPatient::where(function ($qry) use ($q) {
                    $qry->where('id', $q)
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%");
                })
                ->where('encodedfrom_tbdots', 1)
                ->paginate(10);

                $select_view = 'search';
            }
            else {
                if(request()->input('d')) {
                    $sdate = request()->input('d');
                }
                else {
                    $sdate = date('Y-m-d');
                }
    
                $ll = SyndromicRecords::whereDate('created_at', $sdate)
                ->orderBy('created_at', 'DESC')
                ->where('checkup_type', 'CHECKUP')
                ->where('facility_id', auth()->user()->itr_facility_id)
                ->where('encodedfrom_tbdots', 1)
                ->paginate(10);
    
                $select_view = 'home';
            }
        }
        else {
            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                //HOSPITAL VIEW
                $facility_id = auth()->user()->itr_facility_id;

                if(request()->input('q')) {
                    $q = request()->input('q');

                    $ll = SyndromicPatient::where(function ($qry) use ($q) {
                        $qry->where('id', $q)
                        ->orWhere('unique_opdnumber', $q)
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%");
                    })
                    //->where('facility_id', $facility_id)
                    ->paginate(10);

                    $select_view = 'search';
                }
                else {
                    /*
                    if(!(request()->input('showVerified'))) {
                        $ll = SyndromicRecords::where('brgy_verified', 0)
                        ->where('facility_id', $facility_id)
                        ->orderBy('created_at', 'ASC')
                        ->paginate(10);
                    }
                    else {
                        $ll = SyndromicRecords::where('brgy_verified', 1)
                        ->where('facility_id', $facility_id)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);
                    }
                    */

                    if(request()->input('er_view')) {
                        $hosp_identifier = 'ER';
                    }
                    else {
                        $hosp_identifier = 'OPD';
                    }

                    if(request()->input('d')) {
                        $sdate = request()->input('d');
                    }
                    else {
                        $sdate = date('Y-m-d');
                    }

                    $ll = SyndromicRecords::where('hosp_identifier', $hosp_identifier)
                    ->whereDate('created_at', $sdate)
                    ->paginate(10);

                    $select_view = 'home';
                }
            }
            else {
                //BRGY ACCOUNT BRGY VIEW
                if(request()->input('q')) {
                    $q = request()->input('q');
    
                    $ll = SyndromicPatient::where(function ($qry) use ($q) {
                        $qry->where('id', $q)
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%");
                    })
                    ->where('address_brgy_text', auth()->user()->brgy->brgyName)
                    ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                    ->where('address_province_text', auth()->user()->brgy->city->province->provinceName)
                    ->paginate(10);

                    $select_view = 'search';
                }
                else {
                    if(!(request()->input('showVerified'))) {
                        $ll = SyndromicRecords::whereHas('syndromic_patient', function ($q) {
                            $q->where('address_brgy_text', auth()->user()->brgy->brgyName)
                            ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                            ->where('address_province_text', auth()->user()->brgy->city->province->provinceName);
                        })
                        ->where('brgy_verified', 0)
                        ->orderBy('created_at', 'ASC')
                        ->paginate(10);
                    }
                    else {
                        $ll = SyndromicRecords::whereHas('syndromic_patient', function ($q) {
                            $q->where('address_brgy_text', auth()->user()->brgy->brgyName)
                            ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                            ->where('address_province_text', auth()->user()->brgy->city->province->provinceName);
                        })
                        ->where('brgy_verified', 1)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);
                    }

                    $select_view = 'home';
                }
            }
        }

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        if($select_view == 'home') {
            return view('syndromic.home', [
                'list' => $ll,
                'medical_event_list' => $medicalevent_list,

                'brgy_list' => $brgy_list,
            ]);
        }
        else {
            return view('syndromic.search_patient', [
                'list' => $ll,
                'medical_event_list' => $medicalevent_list,
                'search_mode' => 'PATIENT',
            ]);
        }
    }

    public function downloadOpdExcel() {
        $year = request()->input('year');

        $from = request()->input('date_from');
        $to = request()->input('date_to');

        $get_records = SyndromicRecords::where('facility_id', auth()->user()->itr_facility_id);
        

        if($from == $to) {
            $get_records = $get_records->whereDate('consultation_date', $from);
        }
        else {
            $get_records = $get_records->whereBetween('consultation_date', [$from, $to]);
        }

        $get_records = $get_records->orderBy('consultation_date', 'DESC')
        ->get();

        $spreadsheet = IOFactory::load(storage_path('ITR_OPD_RECORD.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        foreach($get_records as $ind => $d) {
            $curtab = $ind + 2;

            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                $opd_number = $d->syndromic_patient->unique_opdnumber;
            }
            else {
                $opd_number = $d->opdno;
            }

            //$sheet->setCellValue('A'.$curtab, '');
            $sheet->setCellValue('A'.$curtab, date('m/d/Y', strtotime($d->consultation_date)));
            $sheet->setCellValueExplicit('B'.$curtab, $opd_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C'.$curtab, $d->syndromic_patient->getName());
            $sheet->setCellValue('D'.$curtab, $d->syndromic_patient->address_brgy_text);
            $sheet->setCellValue('E'.$curtab, $d->syndromic_patient->getStreetPurok());
            $sheet->setCellValue('F'.$curtab, date('m/d/Y', strtotime($d->syndromic_patient->bdate)));
            $sheet->setCellValue('G'.$curtab, $d->syndromic_patient->getAge());
            $sheet->setCellValue('H'.$curtab, substr($d->syndromic_patient->gender,0,1));
            $sheet->setCellValue('I'.$curtab, $d->syndromic_patient->getContactNumber());
            $sheet->setCellValue('J'.$curtab, $d->chief_complain);
            $sheet->setCellValue('K'.$curtab, $d->dcnote_assessment ?: 'N/A');
            $sheet->setCellValue('L'.$curtab, $d->dcnote_plan ?: 'N/A');
            $sheet->setCellValue('M'.$curtab, $d->name_of_physician);
            $sheet->setCellValueExplicit('N'.$curtab, $d->remarks ?: 'N/A', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('O'.$curtab, date('m/d/Y', strtotime($d->created_at)));
            $sheet->setCellValue('P'.$curtab, $d->user->name);
        }

        $fileName = 'CONSULTATION_MASTERLIST_'.$from.'_to_'.$to.'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function newPatient() {
        $lname = request()->input('lname');
        $fname = request()->input('fname');
        $bdate = request()->input('bdate');
        
        $mname = request()->input('mname');
        $suffix = request()->input('suffix');

        $getname = $lname.', '.$fname;

        if(request()->input('mname')) {
            $getname = $getname.' '.$mname;

            if(strlen($mname) < 2) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'ERROR: Middle Name is Invalid. It should be more than or equal to 2 Letters.')
                ->with('msgtype', 'warning');
            }
        }

        if(request()->input('suffix')) {
            $getname = $getname.' '.$suffix;
        }

        $s = SyndromicPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate);

        if(!($s)) {
            //getAge
            $cbdate = Carbon::parse($bdate);
            $getage = $cbdate->diffInYears(Carbon::now());
            
            return view('syndromic.new_patient', [
                'getage' => $getage,
            ]);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Patient ('.mb_strtoupper($getname).') already exists in the database.')
            ->with('p', SyndromicPatient::find($s->id))
            ->with('msgtype', 'warning');
        }

        //new method of checking duplicate before storing records
        /*
        $s = SyndromicPatient::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
        ->whereDate('bdate', $bdate);

        if(request()->input('mname')) {
            $getname = $lname.', '.$fname.' '.$mname;

            $s = $s->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)));
        }
        else {
            $getname = $lname.', '.$fname;
        }

        if(request()->input('suffix')) {
            $suffix = request()->input('suffix');
            $getname = $getname.' '.$suffix;

            $s = $s->where('suffix', $suffix)->first();
        }
        else {
            $s = $s->first();
        }

        if($s) {
            
        }
        else {
            
        }
        */
    }

    public function storePatient(Request $request) {
        if(!(SyndromicPatient::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->suffix, $request->date))) {
            if(date('n') == 1) {
                $sc = 'A';
            }
            else if(date('n') == 2) {
                $sc = 'B';
            }
            else if(date('n') == 3) {
                $sc = 'C';
            }
            else if(date('n') == 4) {
                $sc = 'D';
            }
            else if(date('n') == 5) {
                $sc = 'E';
            }
            else if(date('n') == 6) {
                $sc = 'F';
            }
            else if(date('n') == 7) {
                $sc = 'G';
            }
            else if(date('n') == 8) {
                $sc = 'H';
            }
            else if(date('n') == 9) {
                $sc = 'I';
            }
            else if(date('n') == 10) {
                $sc = 'J';
            }
            else if(date('n') == 11) {
                $sc = 'K';
            }
            else if(date('n') == 12) {
                $sc = 'L';
            }
            
            $foundunique = false;
    
            while(!$foundunique) {
                $qr = date('Y').'-'.$sc.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).chr(mt_rand(65, 90)).chr(mt_rand(65, 90));
    
                $search = SyndromicPatient::where('qr', $qr)->first();
                $search2 = PharmacyPatient::where('qr', $qr)->first();
    
                if(!$search && !$search2) {
                    $foundunique = true;
                }
            }

            //Check if Unique OPD Number Exist before Proceeding
            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                $ucheck = SyndromicPatient::where('unique_opdnumber', $request->unique_opdnumber)->first();

                if($ucheck) {
                    return redirect()->back()
                    ->with('msg', 'Error: Unique Hospital Number already used by other patient.')
                    ->with('msgtype', 'danger');
                }
            }

            $ageToInt = Carbon::parse($request->bdate)->age;

            //Selfie Algo
            if($request->filled('selfie_image')) {
                $imageData = $request->selfie_image;
                
                // Decode the base64 image
                $image = str_replace('data:image/jpeg;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                // Define the file path and name
                $selfie_filename = 'captured_image_' . time() . '.jpg';
                $path = 'patients/'.$selfie_filename;

                // Save the image to the public/uploads folder
                file_put_contents($path, $imageData);
            }
            else {
                $selfie_filename = NULL;
            }

            $values_array = [
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                'bdate' => $request->bdate,
                'gender' => $request->gender,
                'cs' => $request->cs,
                'spouse_name' => ($request->cs == 'MARRIED') ? $request->spouse_name : NULL,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'contact_number2' => $request->contact_number2,

                'isph_member' => ($request->isph_member == 'Y') ? 1 : 0,
                'philhealth' => ($request->filled('philhealth')) ? $request->philhealth : NULL,

                'occupation' => ($request->filled('occupation')) ? mb_strtoupper($request->occupation) : NULL,
                'occupation_place' => ($request->filled('occupation') && request()->filled('occupation_place')) ? mb_strtoupper($request->occupation_place) : NULL,
    
                'mother_name' => $request->mother_name,
                'father_name' => $request->father_name,

                'is_indg' => ($request->is_indg == 'Y') ? 'Y' : 'N',
                'is_4ps' => ($request->is_4ps == 'Y') ? 'Y' : 'N',
                'is_nhts' => ($request->is_nhts == 'Y') ? 'Y' : 'N',
                'is_seniorcitizen' => ($ageToInt >= 60) ? 'Y' : 'N',
                'is_pwd' => ($request->is_pwd == 'Y') ? 'Y' : 'N',
                'is_singleparent' => ($request->is_singleparent == 'Y') ? 'Y' : 'N',
                'is_others' => ($request->is_others == 'Y') ? 'Y' : 'N',
                'is_others_specify' => ($request->is_others == 'Y') ? mb_strtoupper($request->is_others_specify) : NULL,
    
                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,
                'address_street' => $request->filled('address_street') ? mb_strtoupper($request->address_street) : NULL,
                'address_houseno' => $request->filled('address_houseno') ? mb_strtoupper($request->address_houseno) : NULL,
    
                'ifminor_resperson' => ($request->filled('ifminor_resperson')) ? mb_strtoupper($request->ifminor_resperson) : NULL,
                'ifminor_resrelation' => ($request->filled('ifminor_resrelation')) ? mb_strtoupper($request->ifminor_resrelation) : NULL,

                'is_lgustaff' => ($request->is_lgustaff == 'Y') ? 1 : 0,
                'lgu_office_name' => ($request->is_lgustaff == 'Y' && $request->filled('lgu_office_name')) ? mb_strtoupper($request->lgu_office_name) : NULL,
    
                'qr' => $qr,
                'facility_id' => auth()->user()->itr_facility_id,

                'encodedfrom_tbdots' => (auth()->user()->isTbdotsEncoder()) ? 1 : 0,
                'selfie_file' => $selfie_filename,
            ];

            if(auth()->user()->itr_facility_id == 11730) { //Manggahan Facility ID Checking
                $values_array = $values_array + [
                    'facility_controlnumber' => $request->facility_controlnumber,
                ];
            }

            if(!auth()->user()->isSyndromicHospitalLevelAccess()) {
                $values_array = $values_array + [
                    'philhealth_statustype' => ($request->isph_member == 'Y') ? $request->philhealth_statustype : NULL,
                    'family_member' => $request->family_member,
                ];
            }

            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                $values_array = $values_array + [
                    'unique_opdnumber' => mb_strtoupper($request->unique_opdnumber),
                    'id_presented' => mb_strtoupper($request->id_presented),
                ];
            }
    
            $c = $request->user()->syndromicpatient()->create($values_array);
    
            return redirect()->route('syndromic_newRecord', $c->id)
            ->with('msg', 'Patient record successfully created. Proceed by completing the ITR of the patient.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Patient was already encoded and your input was blocked to avoid duplicate entries.')
            ->with('msgtype', 'warning');
        }
    }

    public function newRecord($patient_id) {
        $patient = SyndromicPatient::findOrFail($patient_id);

        if(auth()->user()->isSyndromicHospitalLevelAccess()) {
            $doclist = SyndromicDoctor::where('facility_id', auth()->user()->itr_facility_id)
            ->where('active_in_service', 'Y')
            ->get();

            //Check if Hospital Number is Initialized First
            if(is_null($patient->unique_opdnumber)) {
                return redirect()->back()
                ->with('msg', 'Error: Patient '.$patient->getName().' does not have Hospital Number initialized yet. Please update the patient details first by clicking his/her name below and then try again.')
                ->with('msgtype', 'danger');
            }

            //Check if there are unfinished Consultation Data
            $check_unfinished = SyndromicRecords::where('syndromic_patient_id', $patient->id)
            ->where('hospital_completion', 'PART1')
            ->first();

            if($check_unfinished) {
                return redirect()->back()
                ->with('msg', 'Error: There was some unfinished Medical Records linked to the Patient '.$patient->getName().'. Please complete or delete it first then try again.')
                ->with('msgtype', 'danger');
            }
        }
        else {
            $doclist = SyndromicDoctor::where('active_in_service', 'Y')
            ->get();
        }

        //check if record exist today
        if(!auth()->user()->isTbdotsEncoder()) {
            if(auth()->user()->itr_facility_id == 11730) { //Multiple follow-up on same day on Manggahan
                $existing_record_count = SyndromicRecords::where('syndromic_patient_id', $patient->id)
                ->where('facility_id', auth()->user()->itr_facility_id)
                ->whereDate('created_at', date('Y-m-d'))
                ->count();

                if($existing_record_count >= 2) {
                    $check = true;
                }
                else {
                    $check = false;
                }
            }
            else {
                $check = SyndromicRecords::where('syndromic_patient_id', $patient->id)
                ->where('facility_id', auth()->user()->itr_facility_id)
                ->whereDate('created_at', date('Y-m-d'))
                ->first();
            }
        }
        else {
            $check = false;
        }

        //GET DEFAULT NATURE
        $count_previous = SyndromicRecords::where('syndromic_patient_id', $patient->id);

        $past_comor = '';
        $past_comorfirstfam = '';

        if($count_previous->count() == 0) {
            $get_dnature = 'NEW CONSULTATION/CASE';
        }
        else {
            $get_dnature = NULL;

            $get_latest_record = $count_previous->latest()->first();

            if(!is_null($get_latest_record->comorbid_list)) {
                $past_comor = $get_latest_record->comorbid_list;
            }

            if(!is_null($get_latest_record->firstdegree_comorbid_list)) {
                $past_comorfirstfam = $get_latest_record->firstdegree_comorbid_list;
            }
        }

        //Make some fields required on hospital accounts
        if(auth()->user()->isSyndromicHospitalLevelAccess()) {
            $required_maindiagnosis = true;
            $required_bp = true;
            $required_weight = false;
            $required_height = false;
            $required_symptoms = false; //will be required on part 2
        }
        else {
            $required_maindiagnosis = false;
            $required_bp = false;

            if(!is_null(auth()->user()->itr_medicalevent_id)) {
                $required_symptoms = false;
                $required_weight = false;
                $required_height = false;
            }
            else {
                $required_symptoms = true;
                $required_weight = true;
                $required_height = true;
            }
        }

        if($check) {
            return redirect()->back()
            ->with('msg', 'Error: Patient ITR Record that was encoded today already exists in the server.')
            ->with('msgtype', 'warning');
        }
        else {
            $number_in_line = SyndromicRecords::where('checkup_type', 'CHECKUP')
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->whereDate('created_at', date('Y-m-d'));

            if(auth()->user()->isTbdotsEncoder()) {
                $number_in_line = $number_in_line->where('encodedfrom_tbdots', 1)->count() + 1;
            }
            else {
                $number_in_line = $number_in_line->count() + 1;
            }

            //OLD OR NEW
            $count_consult = SyndromicRecords::where('syndromic_patient_id', $patient->id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->count();

            if($count_consult <= 0) {
                $new_patient = true;
            }
            else {
                $new_patient = false;
            }

            return view('syndromic.new_record', [
                'patient' => $patient,
                'doclist' => $doclist,
                'number_in_line' => $number_in_line,
                'get_dnature' => $get_dnature,
                'new_patient' => $new_patient,
                'past_comor' => $past_comor,
                'past_comorfirstfam' => $past_comorfirstfam,

                'required_maindiagnosis' => $required_maindiagnosis,
                'required_bp' => $required_bp,
                'required_height' => $required_height,
                'required_weight' => $required_weight,
                'required_symptoms' => $required_symptoms,
            ]);
        }
    }

    public function storeRecord($patient_id, Request $r) {
        $p = SyndromicPatient::findOrFail($patient_id);

        $birthdate = Carbon::parse($p->bdate);
        $currentDate = Carbon::parse(date('Y-m-d'));

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        //opd number generator

        $patient_yearcount = SyndromicRecords::whereYear('created_at', date('Y'))->count() + 1;
        
        $getopd_num = date('Y').'-'.$patient_yearcount;

        $check1 = SyndromicRecords::where('syndromic_patient_id', $p->id)
        ->where('facility_id', auth()->user()->itr_facility_id)
        ->whereDate('created_at', date('Y-m-d'))
        ->first();

        $foundunique = false;
        while(!$foundunique) {
            $for_qr = mb_strtoupper(Str::random(6));
            
            $search = SyndromicRecords::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }
        
        //permission check
        $perm_list = explode(",", auth()->user()->permission_list);

        //GET LAST CHECK UP
        $lastcheckup = SyndromicRecords::where('syndromic_patient_id', $p->id)
        ->orderBy('consultation_date', 'DESC')
        ->first();

        //PRESCRIPTION OPTION
        if($r->prescribe_option == 'Y') {
            if(is_array($r->prescription_list)) {
                $get_meds = implode(',', $r->prescription_list);
            }
            else {
                $get_meds = $r->prescription_list;
            }
        }

        if(!$check1) {
            $values_array = [
                'status' => 'approved',
                'facility_id' => auth()->user()->itr_facility_id,
                'medical_event_id' => (!is_null(auth()->user()->itr_medicalevent_id)) ? auth()->user()->itr_medicalevent_id : NULL,
                'checkup_type' => $r->checkup_type,
                'chief_complain' => mb_strtoupper($r->chief_complain),
                'nature_of_visit' => $r->nature_of_visit,
                'consultation_type' => implode(',', $r->consultation_type),
                'rx_outsidecho' => ($r->checkup_type == 'REQUEST_MEDS') ? 1 : 0,
                'outsidecho_name' => ($r->checkup_type == 'REQUEST_MEDS' && $r->filled('outsidecho_name')) ? mb_strtoupper($r->outsidecho_name) : NULL,
                'syndromic_patient_id' => $p->id,
                'opdno' => $getopd_num,
                'line_number' => $r->line_number,
                'last_checkup_date' => ($lastcheckup) ? date('Y-m-d', strtotime($lastcheckup->consultation_date)) : NULL,
                'consultation_date' => $r->consultation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => $r->filled('bloodpressure') ? $r->bloodpressure : NULL,
                'weight' => ($r->filled('weight')) ? $r->weight : NULL,
                'respiratoryrate' => $r->respiratoryrate,
                'pulserate' => $r->pulserate,
                'saturationperioxigen' => $r->saturationperioxigen,

                'brgy_verified' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? 1 : 0,
                'brgy_verified_date' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? date('Y-m-d H:i:s') : NULL,
                'brgy_verified_by' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? auth()->user()->id : NULL,

                'cesu_verified' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? 1 : 0,
                'cesu_verified_date' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? date('Y-m-d H:i:s') : NULL,
                'cesu_verified_by' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? auth()->user()->id : NULL,
    
                'age_years' => $get_ageyears,
                'age_months' => $get_agemonths,
                'age_days' => $get_agedays,

                'qr' => $for_qr,

                'encodedfrom_tbdots' => (auth()->user()->isTbdotsEncoder()) ? 1 : 0,
            ];

            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                $values_array = $values_array + [
                    'hospital_completion' => 'PART1',
                    'hosp_identifier' => $r->hosp_identifier,
                    'o2sat' => $r->o2sat,
                    'is_pregnant' => ($r->is_pregnant == 'Y') ? 1 : 0,
                    'lmp' => ($r->is_pregnant == 'Y') ? $r->lmp : NULL,
                    'edc' => ($r->is_pregnant == 'Y') ? $r->edc : NULL,

                    //'procedure_done' => $r->procedure_done,
                    //'disposition' => $r->disposition,
                    //'is_discharged' => $r->is_discharged,
                    //'date_discharged' => ($r->is_discharged == 'Y') ? $r->date_discharged : NULL,

                    //'tags' => $r->tags,
                ];
            }
            else {
                $values_array = $values_array + [
                    'fever' => ($r->fever_yn) ? 1 : 0,
                    'fever_onset' => ($r->fever_yn) ? $r->fever_onset : NULL,
                    'fever_remarks' => ($r->fever_yn) ? $r->fever_remarks : NULL,
                    'rash' => ($r->rash_yn) ? 1 : 0,
                    'rash_isMaculopapular' => ($r->rash_yn && $r->rash_isMaculopapular) ? 1 : 0,
                    'rash_isPetechia' => ($r->rash_yn && $r->rash_isPetechia) ? 1 : 0,
                    'rash_isPurpura' => ($r->rash_yn && $r->rash_isPurpura) ? 1 : 0,
                    'rash_onset' => ($r->rash_yn) ? $r->rash_onset : NULL,
                    'rash_remarks' => ($r->rash_yn) ? $r->rash_remarks : NULL,
                    'cough' => ($r->cough_yn) ? 1 : 0,
                    'cough_onset' => ($r->cough_yn) ? $r->cough_onset : NULL,
                    'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
                    'colds' => ($r->colds_yn) ? 1 : 0,
                    'colds_onset' => ($r->colds_yn) ? $r->colds_onset : NULL,
                    'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
                    'conjunctivitis' => ($r->conjunctivitis_yn) ? 1 : 0,
                    'conjunctivitis_onset' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_onset : NULL,
                    'conjunctivitis_remarks' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_remarks : NULL,
                    'mouthsore' => ($r->mouthsore_yn) ? 1 : 0,
                    'mouthsore_onset' => ($r->mouthsore_yn) ? $r->mouthsore_onset : NULL,
                    'mouthsore_remarks' => ($r->mouthsore_yn) ? $r->mouthsore_remarks : NULL,
                    'sorethroat' => ($r->sorethroat_yn) ? 1 : 0,
                    'sorethroat_onset' => ($r->sorethroat_yn) ? $r->sorethroat_onset : NULL,
                    'sorethroat_remarks' => ($r->sorethroat_yn) ? $r->sorethroat_remarks : NULL,
                    'lossoftaste' => ($r->lossoftaste_yn) ? 1 : 0,
                    'lossoftaste_onset' => ($r->lossoftaste_yn) ? $r->lossoftaste_onset : NULL,
                    'lossoftaste_remarks' => ($r->lossoftaste_yn) ? $r->lossoftaste_remarks : NULL,
                    'lossofsmell' => ($r->lossofsmell_yn) ? 1 : 0,
                    'lossofsmell_onset' => ($r->lossofsmell_yn) ? $r->lossofsmell_onset : NULL,
                    'lossofsmell_remarks' => ($r->lossofsmell_yn) ? $r->lossofsmell_remarks : NULL,
                    'headache' => ($r->headache_yn) ? 1 : 0,
                    'headache_onset' => ($r->headache_yn) ? $r->headache_onset : NULL,
                    'headache_remarks' => ($r->headache_yn) ? $r->headache_remarks : NULL,
                    'jointpain' => ($r->jointpain_yn) ? 1 : 0,
                    'jointpain_onset' => ($r->jointpain_yn) ? $r->jointpain_onset : NULL,
                    'jointpain_remarks' => ($r->jointpain_yn) ? $r->jointpain_remarks : NULL,
                    'musclepain' => ($r->musclepain_yn) ? 1 : 0,
                    'musclepain_onset' => ($r->musclepain_yn) ? $r->musclepain_onset : NULL,
                    'musclepain_remarks' => ($r->musclepain_yn) ? $r->musclepain_remarks : NULL,
                    'diarrhea' => ($r->diarrhea_yn) ? 1 : 0,
                    'bloody_stool' => ($r->diarrhea_yn && $r->bloody_stool) ? 1 : 0,
                    'diarrhea_onset' => ($r->diarrhea_yn) ? $r->diarrhea_onset : NULL,
                    'diarrhea_remarks' => ($r->diarrhea_yn) ? $r->diarrhea_remarks : NULL,
                    'abdominalpain' => ($r->abdominalpain_yn) ? 1 : 0,
                    'abdominalpain_onset' => ($r->abdominalpain_yn) ? $r->abdominalpain_onset : NULL,
                    'abdominalpain_remarks' => ($r->abdominalpain_yn) ? $r->abdominalpain_remarks : NULL,
                    'vomiting' => ($r->vomiting_yn) ? 1 : 0,
                    'vomiting_onset' => ($r->vomiting_yn) ? $r->vomiting_onset : NULL,
                    'vomiting_remarks' => ($r->vomiting_yn) ? $r->vomiting_remarks : NULL,
                    'weaknessofextremities' => ($r->weaknessofextremities_yn) ? 1 : 0,
                    'weaknessofextremities_onset' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_onset : NULL,
                    'weaknessofextremities_remarks' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_remarks : NULL,
                    'paralysis' => ($r->paralysis_yn) ? 1 : 0,
                    'paralysis_onset' => ($r->paralysis_yn) ? $r->paralysis_onset : NULL,
                    'paralysis_remarks' => ($r->paralysis_yn) ? $r->paralysis_remarks : NULL,
                    'alteredmentalstatus' => ($r->alteredmentalstatus_yn) ? 1 : 0,
                    'alteredmentalstatus_onset' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_onset : NULL,
                    'alteredmentalstatus_remarks' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_remarks : NULL,
                    'animalbite' => ($r->animalbite_yn) ? 1 : 0,
                    'animalbite_onset' => ($r->animalbite_yn) ? $r->animalbite_onset : NULL,
                    'animalbite_remarks' => ($r->animalbite_yn) ? $r->animalbite_remarks : NULL,
                    'anorexia' => ($r->anorexia_yn) ? 1 : 0,
                    'anorexia_onset' => ($r->anorexia_yn) ? $r->anorexia_onset : NULL,
                    'anorexia_remarks' => ($r->anorexia_yn) ? $r->anorexia_remarks : NULL,
                    'jaundice' => ($r->jaundice_yn) ? 1 : 0,
                    'jaundice_onset' => ($r->jaundice_yn) ? $r->jaundice_onset : NULL,
                    'jaundice_remarks' => ($r->jaundice_yn) ? $r->jaundice_remarks : NULL,
                    'nausea' => ($r->nausea_yn) ? 1 : 0,
                    'nausea_onset' => ($r->nausea_yn) ? $r->nausea_onset : NULL,
                    'nausea_remarks' => ($r->nausea_yn) ? $r->nausea_remarks : NULL,
                    'fatigue' => ($r->fatigue_yn) ? 1 : 0,
                    'fatigue_onset' => ($r->fatigue_yn) ? $r->fatigue_onset : NULL,
                    'fatigue_remarks' => ($r->fatigue_yn) ? $r->fatigue_remarks : NULL,
                    'dyspnea' => ($r->dyspnea_yn) ? 1 : 0,
                    'dyspnea_onset' => ($r->dyspnea_yn) ? $r->dyspnea_onset : NULL,
                    'dyspnea_remarks' => ($r->dyspnea_yn) ? $r->dyspnea_remarks : NULL,
                    'other_symptoms' => ($r->other_symptoms_yn) ? 1 : 0,
                    'other_symptoms_onset' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset : NULL,
                    'other_symptoms_onset_remarks' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset_remarks : NULL,

                    'is_hospitalized' => ($r->is_hospitalized == 'Y') ? 1 : 0,
                    'date_admitted' => ($r->is_hospitalized == 'Y') ? $r->date_admitted : NULL,
                    'date_released' => ($r->is_hospitalized == 'Y') ? $r->date_released : NULL,

                    'outcome' => $r->outcome,
                    'outcome_recovered_date' => ($r->outcome == 'RECOVERED') ? $r->outcome_recovered_date : NULL,
                    'outcome_died_date' => ($r->outcome == 'DIED') ? $r->outcome_died_date : NULL,

                    //'bigmessage' => $r->bigmessage,
                    'diagnosis_type' => $r->diagnosis_type,
                    'dcnote_assessment' => ($r->filled('dcnote_assessment')) ? mb_strtoupper($r->dcnote_assessment) : NULL,
                    'main_diagnosis' => ($r->filled('main_diagnosis')) ? implode('|', $r->main_diagnosis) : NULL,
                    'dcnote_plan' => ($r->filled('dcnote_plan')) ? mb_strtoupper($r->dcnote_plan) : NULL,
                    'dcnote_diagprocedure' => ($r->filled('dcnote_diagprocedure')) ? mb_strtoupper($r->dcnote_diagprocedure) : NULL,
                    //'other_diagnosis' => ($r->filled('other_diagnosis')) ? implode(',', $r->other_diagnosis) : NULL,
                    //'rx' => ($r->filled('rx')) ? mb_strtoupper($r->rx) : NULL,
                    'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,

                    'prescribe_option' => $r->prescribe_option,
                    'prescription_list' => ($r->prescribe_option == 'Y') ? $get_meds : NULL,

                    'comorbid_list' => ($r->filled('comorbid_list')) ? implode(',', $r->comorbid_list) : NULL,
                    'firstdegree_comorbid_list' => ($r->filled('firstdegree_comorbid_list')) ? implode(',', $r->firstdegree_comorbid_list) : NULL,

                    'alert_list' => ($r->filled('alert_list')) ? implode(',', $r->alert_list) : NULL,
                    'alert_ifdisability_list' => ($r->filled('alert_ifdisability_list') && in_array('DISABILITY', $r->alert_list)) ? implode(',', $r->alert_ifdisability_list) : NULL,
                    'alert_description' => ($r->filled('alert_description')) ? mb_strtoupper($r->alert_description) : NULL,

                    'laboratory_request_list' => ($r->filled('laboratory_request_list')) ? implode(',', $r->laboratory_request_list) : NULL,
                    'imaging_request_list' => ($r->filled('imaging_request_list')) ? implode(',', $r->imaging_request_list) : NULL,

                    'name_of_physician' => $r->name_of_physician,
                    'other_doctor' => ($r->name_of_physician == 'OTHERS') ? mb_strtoupper($r->other_doctor) : NULL,
                    'dru_name'=> ($r->name_of_physician != 'OTHERS') ? SyndromicDoctor::where('doctor_name', $r->name_of_physician)->first()->dru_name : NULL,
                ];
            }

            $c = $r->user()->syndromicrecord()->create($values_array);

            //Auto Create Pharmacy Account
            $pharmacy_check = PharmacyPatient::where('itr_id', $p->id)->first();
            
            if(!($pharmacy_check)) {
                $foundunique = false;

                while(!$foundunique) {
                    $global_qr = Str::random(20);

                    $search = PharmacyPatient::where('global_qr', $global_qr)->first();
                    if(!$search) {
                        $foundunique = true;
                    }
                }

                $create_pharma = $r->user()->pharmacypatient()->create([
                    'lname' => $p->lname,
                    'fname' => $p->fname,
                    'mname' => $p->mname,
                    'suffix' => $p->suffix,
                    'bdate' => $p->bdate,
                    'gender' => $p->gender,
                    'email' => $p->email,
                    'contact_number' => $p->contact_number,
                    'contact_number2' => $p->contact_number2,
                    'philhealth' => $p->philhealth,
            
                    'address_region_code' => $p->address_region_code,
                    'address_region_text' => $p->address_region_text,
                    'address_province_code' => $p->address_province_code,
                    'address_province_text' => $p->address_province_text,
                    'address_muncity_code' => $p->address_muncity_code,
                    'address_muncity_text' => $p->address_muncity_text,
                    'address_brgy_code' => $p->address_brgy_code,
                    'address_brgy_text' => $p->address_brgy_text,
                    'address_street' => $p->address_street,
                    'address_houseno' => $p->address_houseno,
                    
                    //'concerns_list' => NULL, //for creation ng pharmacy encoder
                    'qr' => $p->qr,
                    'global_qr' => $global_qr,
            
                    'id_file' => NULL,
                    'selfie_file' => NULL,
            
                    'status' => 'ENABLED',

                    'from_outside' => ($r->checkup_type == 'REQUEST_MEDS') ? 1: 0,
                    'outside_name' => ($r->checkup_type == 'REQUEST_MEDS' && $r->filled('outsidecho_name')) ? mb_strtoupper($r->outsidecho_name) : NULL,
                    'itr_id' => $p->id,
                    'pharmacy_branch_id' => auth()->user()->opdfacility->pharmacy_branch_id,
                    
                    'is_lgustaff' => $p->is_lgustaff,
                    'lgu_office_name' => $p->lgu_office_name,
                ]);
            }
            else {
                $create_pharma = PharmacyPatient::findOrFail($pharmacy_check->id);
            }

            if($r->prescribe_option == 'Y') {
                //PHARMA REASON LIST ALGORITHM
                $s_prescription = PharmacyPrescription::where('patient_id', $create_pharma->id)
                ->where('finished', 0)
                ->update([
                    'finished' => 1,
                ]);

                //Create Prescription and Automate Reason for Meds
                $c_prescription = $r->user()->pharmacyprescription()->create([
                    'patient_id' => $create_pharma->id,
                    'concerns_list' => NULL,
                ]);

                $search_cart = $create_pharma->getPendingCartMain();
                
                if(!($search_cart)) {
                    $search_cart = request()->user()->pharmacycartmain()->create([
                        'patient_id' => $create_pharma->id,
                        'prescription_id' => $c_prescription->id,
                        'branch_id' => auth()->user()->pharmacy_branch_id,
                    ]);
                }

                $usage_arr_temp = [];
                $belong_temp = [];

                foreach($r->prescription_list as $psub) {
                    $csub_store = PharmacyCartSub::create([
                        'main_cart_id' => $search_cart->id,
                        'subsupply_id' => $psub,
                        'qty_to_process' => 0,
                        'type_to_process' => 'PIECE',
                    ]);

                    //AUTO ADD PRESCRIPTION USAGE CATEGORY

                    //GET USAGE CATEGORY
                    
                    //BASED ON SYMPTOMS
                    if(in_array('INJURY', $r->consultation_type)) {
                        array_push($belong_temp, 'ACCIDENT/INJURIES/WOUNDS');
                    }

                    if($p->getAgeInt() <= 11) {
                        array_push($belong_temp, 'CHILDREN');
                    }

                    if($r->colds_yn) {
                        array_push($belong_temp, 'COLDS');
                    }

                    if(!is_null($r->comorbid_list)) {
                        if(in_array('DIABETES', $r->comorbid_list)) {
                            array_push($belong_temp, 'DIABETES');
                        }

                        if(in_array('HEART DISEASE', $r->comorbid_list) || in_array('HEART ATTACK', $r->comorbid_list) || in_array('HYPERTENSION', $r->comorbid_list)) {
                            array_push($belong_temp, 'HYPERTENSION/HEART/HIGH CHOLESTEROL');
                        }
    
                        if(in_array('KIDNEY DISEASE', $r->comorbid_list)) {
                            array_push($belong_temp, 'KIDNEY PROBLEM');
                        }

                        if(in_array('NEUROLOGICAL DISEASE', $r->comorbid_list)) {
                            array_push($belong_temp, 'NERVES PROBLEM');
                        }

                        if(in_array('TUBERCOLOSIS', $r->comorbid_list) || in_array('TUBERCOLOSIS', $r->consultation_type)) {
                            array_push($belong_temp, 'TB-DOTS');
                        }
    
                        if(in_array('KIDNEY DISEASE', $r->comorbid_list)) {
                            array_push($belong_temp, 'DIALYSIS');
                        }
    
                        if(in_array('GASTROINTESTINAL (GIT)', $r->comorbid_list)) {
                            array_push($belong_temp, 'GIT');
                        }
    
                        if(in_array('CANCER', $r->comorbid_list)) {
                            array_push($belong_temp, 'CHEMOTHERAPHY/CANCER');
                        }

                        if(in_array('ASTHMA', $r->comorbid_list)) {
                            array_push($belong_temp, 'ASTHMA');
                        }
                    }
                    
                    if($r->rash_yn) {
                        array_push($belong_temp, 'DERMA/SKIN PROBLEM');
                    }

                    if(in_array('FAMILY PLANNING', $r->consultation_type)) {
                        array_push($belong_temp, 'FAMILY PLANNING');
                    }

                    if($r->headache_yn || $r->fever_yn) {
                        array_push($belong_temp, 'FEVER/HEADACHE');
                    }

                    if($r->jaundice_yn) {
                        array_push($belong_temp, 'LIVER PROBLEM');
                    }

                    if($r->musclepain_yn) {
                        array_push($belong_temp, 'MUSCLE PROBLEM');
                    }

                    if($r->cough_yn || $r->dyspnea_yn) {
                        array_push($belong_temp, 'RESPIRATORY PROBLEM');
                    }

                    if(!is_null($r->alert_list)) {
                        if(in_array('ALLERGY', $r->alert_list)) {
                            array_push($belong_temp, 'ALLERGY');
                        }
                    }

                    if($r->diarrhea_yn) {
                        array_push($belong_temp, 'DIARRHEA');
                    }

                    $empty_cat = [
                        'IMMUNE DEFICIENCY',
                        'IMMUNIZATION',
                        'INFECTION',
                        'MENTAL HEALTH',
                        'MICROBIAL INFECTIONS',
                        'MILD/SEVERE PAIN',
                        'URIC ACID',
                        'VERTIGO/DIZZY',
                        'UTI',
                        'TOOTH ACHE',
                        'INSOMIA',
                        'PREGNANT',
                        'ELECTROLYTES DEFFICIENT',
                        'BLEEDING',
                        'ANTICOAGULANT',
                        'FUNGAL INFECTION',
                        'ANTI-AMOEBA',
                        'DIURETIC',
                        'CORTICOSTEROIDS',
                        'ANESTHESIA',
                        'EAR INFECTION',
                    ];
                    
                    //BASED ON COMORBIDS
                    $sea_subcart = PharmacySupplySub::findOrFail($psub);
                    if(!is_null($sea_subcart->pharmacysupplymaster->usage_category)) {
                        foreach($belong_temp as $arraya) {
                            if(in_array($arraya, explode(',', $sea_subcart->pharmacysupplymaster->usage_category)) && !in_array($arraya, $usage_arr_temp)) {
                                $usage_arr_temp[] = $arraya;
                            }
                            else {
                                foreach($empty_cat as $arrayb) {
                                    if(in_array($arrayb, explode(',', $sea_subcart->pharmacysupplymaster->usage_category)) && !in_array($arrayb, $usage_arr_temp)) {
                                        $usage_arr_temp[] = $arrayb;
                                    }
                                }
                            }
                        }
                    }
                }

                $upd_prescription = PharmacyPrescription::findOrFail($c_prescription->id);
                $upd_prescription->concerns_list = (!empty($usage_arr_temp)) ? implode(',', $usage_arr_temp) : NULL;
                if($upd_prescription->isDirty()) {
                    $upd_prescription->save();
                }
            }

            //UPDATE SUSPECTED DISEASE LIST
            $fetch_record = SyndromicRecords::find($c->id);
            $fetch_record->generated_susdiseaselist = ($fetch_record->getListOfSuspDiseases() != 'N/A') ? $fetch_record->getListOfSuspDiseases() : NULL;
            if($fetch_record->isDirty()) {
                $fetch_record->save();
            }

            if($c->ifHasImmediateNotifiable()) {
                $immediatenotifiable = 1;
            }
            else {
                $immediatenotifiable = 0;
            }

            return redirect()->route('syndromic_home', ['opd_view' => 1])
            ->with('msg', 'Patient Medical Record was created successfully.')
            ->with('option_medcert', $c->id)
            ->with('option_pharmacy', $create_pharma->id)
            ->with('immediate_notifiable', $immediatenotifiable)
            ->with('fetchr', $c)
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient ITR Record that was encoded today already exists in the server.')
            ->with('msgtype', 'warning');
        }        
    }

    public function viewPatient($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $sal = User::where('id', '!=', auth()->user()->id)
        ->where('id', '!=', $d->created_by)
        ->where(function ($q) {
            $q->where('permission_list', 'LIKE', '%ITR_BRGY_ADMIN')
            ->orWhere('permission_list', 'LIKE', '%ITR_BRGY_ENCODER');
        })->get();

        $hasRecord_check = SyndromicRecords::where('syndromic_patient_id', $d->id)
        ->first();

        if($hasRecord_check) {
            $has_record = true;
        }
        else {
            $has_record = false;
        }

        if($d->userHasPermissionToAccess()) {
            return view('syndromic.edit_patient', [
                'd' => $d,
                'sal' => $sal,
                'has_record' => $has_record,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updatePatient($patient_id, Request $request) {
        $lname = $request->lname;
        $fname = $request->fname;
        $bdate = $request->bdate;
        
        $mname = $request->mname;
        $suffix = $request->suffix;

        if($request->filled('mname')) {
            if(strlen($mname) < 2) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'ERROR: Middle Name is Invalid. It should be more than or equal to 2 Letters.')
                ->with('msgtype', 'warning');
            }
        }

        $s = SyndromicPatient::ifDuplicateFoundOnUpdate($patient_id, $lname, $fname, $mname, $suffix, $bdate);

        if(!($s)) {
            $getpatient = SyndromicPatient::findOrFail($patient_id);

            if($getpatient->userHasPermissionToShareAccess()) {
                $sharedAccessList = (!is_null($request->shared_access_list)) ? implode(",", $request->shared_access_list) : NULL;
            }
            else {
                $sharedAccessList = $getpatient->shared_access_list;
            }

            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                if(is_null($getpatient->unique_opdnumber)) {
                    $ucheck = SyndromicPatient::where('unique_opdnumber', $request->unique_opdnumber)->first();

                    if($ucheck) {
                        return redirect()->back()
                        ->with('msg', 'Error: Hospital Number already used by other patient. Kindly change and try again.')
                        ->with('msgtype', 'danger');
                    }
                    else {
                        $getpatient->unique_opdnumber = $request->unique_opdnumber;
                    }
                }
                else {
                    $getpatient->unique_opdnumber = $getpatient->unique_opdnumber;
                }

                $getpatient->id_presented = $request->id_presented;
            }
            else {
                $getpatient->unique_opdnumber = $getpatient->unique_opdnumber;
                $getpatient->id_presented = $getpatient->id_presented;
            }

            if($getpatient->isDirty()) {
                $getpatient->save();
            }

            $ageToInt = Carbon::parse($request->bdate)->age;

            //Selfie Algo
            if($request->filled('selfie_image')) {
                $imageData = $request->selfie_image;
                
                // Decode the base64 image
                $image = str_replace('data:image/jpeg;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                // Define the file path and name
                $selfie_filename = 'captured_image_' . time() . '.jpg';
                $path = 'patients/'.$selfie_filename;

                // Save the image to the public/uploads folder
                file_put_contents($path, $imageData);

                //Delete old File
                if(!is_null($getpatient->selfie_file)) {
                    File::delete('patients/'.$getpatient->selfie_file);
                }
            }
            else {
                $selfie_filename = $getpatient->selfie_file;
            }

            $values_array = [
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                'bdate' => $request->bdate,
                'gender' => $request->gender,
                'cs' => $request->cs,
                'spouse_name' => ($request->cs == 'MARRIED') ? $request->spouse_name : NULL,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'contact_number2' => $request->contact_number2,

                'isph_member' => ($request->isph_member == 'Y') ? 1 : 0,
                'philhealth' => ($request->filled('philhealth')) ? $request->philhealth : NULL,

                'occupation' => ($request->filled('occupation')) ? mb_strtoupper($request->occupation) : NULL,
                'occupation_place' => ($request->filled('occupation') && request()->filled('occupation_place')) ? mb_strtoupper($request->occupation_place) : NULL,

                'mother_name' => $request->mother_name,
                'father_name' => $request->father_name,

                'is_indg' => ($request->is_indg == 'Y') ? 'Y' : 'N',
                'is_4ps' => ($request->is_4ps == 'Y') ? 'Y' : 'N',
                'is_nhts' => ($request->is_nhts == 'Y') ? 'Y' : 'N',
                'is_seniorcitizen' => ($ageToInt >= 60) ? 'Y' : 'N',
                'is_pwd' => ($request->is_pwd == 'Y') ? 'Y' : 'N',
                'is_singleparent' => ($request->is_singleparent == 'Y') ? 'Y' : 'N',
                'is_others' => ($request->is_others == 'Y') ? 'Y' : 'N',
                'is_others_specify' => ($request->is_others == 'Y') ? mb_strtoupper($request->is_others_specify) : NULL,

                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,
                'address_street' => $request->filled('address_street') ? mb_strtoupper($request->address_street) : NULL,
                'address_houseno' => $request->filled('address_houseno') ? mb_strtoupper($request->address_houseno) : NULL,

                'ifminor_resperson' => ($request->filled('ifminor_resperson')) ? mb_strtoupper($request->ifminor_resperson) : NULL,
                'ifminor_resrelation' => ($request->filled('ifminor_resrelation')) ? mb_strtoupper($request->ifminor_resrelation) : NULL,

                'shared_access_list' => $sharedAccessList,
                'updated_by' => auth()->user()->id,

                'is_lgustaff' => ($request->is_lgustaff == 'Y') ? 1 : 0,
                'lgu_office_name' => ($request->is_lgustaff == 'Y' && $request->filled('lgu_office_name')) ? mb_strtoupper($request->lgu_office_name) : NULL,
                'selfie_file' => $selfie_filename,
            ];

            if($getpatient->facility_id == 11730 && auth()->user()->itr_facility_id == 11730) { //Manggahan Facility ID Checking
                $values_array = $values_array + [
                    'facility_controlnumber' => $request->facility_controlnumber,
                ];
            }

            if(!auth()->user()->isSyndromicHospitalLevelAccess()) {
                $values_array = $values_array + [
                    'philhealth_statustype' => ($request->isph_member == 'Y') ? $request->philhealth_statustype : NULL,
                    'family_member' => $request->family_member,
                ];
            }

            if(auth()->user()->isSyndromicHospitalLevelAccess()) {
                $values_array = $values_array + [
                    'unique_opdnumber' => (is_null($getpatient->unique_opdnumber)) ? mb_strtoupper($request->unique_opdnumber) : $getpatient->unique_opdnumber,
                    'id_presented' => mb_strtoupper($request->id_presented),
                ];
            }

            $u = SyndromicPatient::where('id', $patient_id)
            ->update($values_array);

            //Also update Pharmacy Record
            $pharma_record = PharmacyPatient::where('itr_id', $patient_id)->first();

            if($pharma_record) {
                $pharma_record->update([
                    'lname' => mb_strtoupper($request->lname),
                    'fname' => mb_strtoupper($request->fname),
                    'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                    'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                    'bdate' => $request->bdate,
                    'gender' => $request->gender,
                    
                    'address_region_code' => $request->address_region_code,
                    'address_region_text' => $request->address_region_text,
                    'address_province_code' => $request->address_province_code,
                    'address_province_text' => $request->address_province_text,
                    'address_muncity_code' => $request->address_muncity_code,
                    'address_muncity_text' => $request->address_muncity_text,
                    'address_brgy_code' => $request->address_brgy_text,
                    'address_brgy_text' => $request->address_brgy_text,
                    'address_street' => $request->filled('address_street') ? mb_strtoupper($request->address_street) : NULL,
                    'address_houseno' => $request->filled('address_houseno') ? mb_strtoupper($request->address_houseno) : NULL,
                ]);
            }

            return redirect()->back()
            ->with('msg', 'Patient record was updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Cannot update record. Patient name already exists.')
            ->with('msgtype', 'warning');
        }

        /*
        //new method of checking duplicate before storing records
        $s = SyndromicPatient::where('id', '!=', $patient_id)
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
        ->whereDate('bdate', $bdate);

        if($request->filled('mname')) {
            $getname = $lname.', '.$fname.' '.$mname;

            $s = $s->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)));
        }
        else {
            $getname = $lname.', '.$fname;
        }

        if($request->filled('suffix')) {
            $suffix = $request->suffix;
            $getname = $getname.' '.$suffix;

            $s = $s->where('suffix', $suffix)->first();
        }
        else {
            $s = $s->first();
        }

        if($s) {
            return redirect()->back()
            ->with('msg', 'Cannot update record. Patient name already exists.')
            ->with('msgtype', 'warning');
        }
        else {
            
        }
        */
    }

    public function deletePatient($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        if($d->hasPermissionToDelete()) {
            //also delete records associated with the patient
            $r = SyndromicRecords::where('syndromic_patient_id', $patient_id)->delete();

            $d->delete();
            
            return redirect()->route('syndromic_home')
            ->with('msg', 'Patient data was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function viewExistingRecordList($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $list = SyndromicRecords::where('syndromic_patient_id', $d->id)
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('syndromic.view_existing_records', [
            'd' => $d,
            'list' => $list,
        ]);
    }

    public function viewRecord($record_id) {
        $r = SyndromicRecords::findOrFail($record_id);
        if(auth()->user()->isSyndromicHospitalLevelAccess()) {
            $doclist = SyndromicDoctor::where('facility_id', auth()->user()->itr_facility_id)
            ->get();
        }
        else {
            $doclist = SyndromicDoctor::get();
        }

        //Make some fields required on hospital accounts
        if(auth()->user()->isSyndromicHospitalLevelAccess()) {
            $required_symptoms = true;
            $required_maindiagnosis = true;
            $required_bp = true;
            $required_weight = false;
            $required_height = false;

            if($r->hospital_completion == 'PART1') {
                $unlocktoolbar = false;
            }
            else {
                $unlocktoolbar = true;
            }
        }
        else {
            if(!is_null($r->medical_event_id)) {
                $required_symptoms = false;
                $required_weight = false;
                $required_height = false;
            }
            else {
                $required_symptoms = true;
                $required_weight = true;
                $required_height = true;
            }

            $required_maindiagnosis = false;
            $required_bp = false;
            

            $unlocktoolbar = true;
        }

        if($r->syndromic_patient->userHasPermissionToAccess()) {
            return view('syndromic.edit_record', [
                'd' => $r,
                'doclist' => $doclist,

                'required_maindiagnosis' => $required_maindiagnosis,
                'required_bp' => $required_bp,
                'unlocktoolbar' => $unlocktoolbar,
                'required_symptoms' => $required_symptoms,
                'required_weight' => $required_weight,
                'required_height' => $required_height,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updateRecord($record_id, Request $r) {
        $d = SyndromicRecords::findOrFail($record_id);

        if(!($d->hasPermissionToUpdate())) {
            return abort(401);
        }

        $perm_list = explode(",", auth()->user()->permission_list);
        
        if($r->submit == 'update') {
            $values_array = [
                //'nature_of_visit' => $r->nature_of_visit,
                'consultation_type' => implode(',', $r->consultation_type),
                'checkup_type' => $r->checkup_type,
                'line_number' => $r->line_number,
                'chief_complain' => mb_strtoupper($r->chief_complain),
                'rx_outsidecho' => ($r->checkup_type == 'REQUEST_MEDS') ? 1 : 0,
                'outsidecho_name' => ($r->checkup_type == 'REQUEST_MEDS' && $r->filled('outsidecho_name')) ? mb_strtoupper($r->outsidecho_name) : NULL,
                'consultation_date' => $r->consultation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => ($r->filled('bloodpressure')) ? $r->bloodpressure : NULL,
                'weight' => $r->weight,
                'respiratoryrate' => $r->respiratoryrate,
                'pulserate' => $r->pulserate,
                'saturationperioxigen' => $r->saturationperioxigen,

                'fever' => ($r->fever_yn) ? 1 : 0,
                'fever_onset' => ($r->fever_yn) ? $r->fever_onset : NULL,
                'fever_remarks' => ($r->fever_yn) ? $r->fever_remarks : NULL,
                'rash' => ($r->rash_yn) ? 1 : 0,
                'rash_isMaculopapular' => ($r->rash_yn && $r->rash_isMaculopapular) ? 1 : 0,
                'rash_isPetechia' => ($r->rash_yn && $r->rash_isPetechia) ? 1 : 0,
                'rash_isPurpura' => ($r->rash_yn && $r->rash_isPurpura) ? 1 : 0,
                'rash_onset' => ($r->rash_yn) ? $r->rash_onset : NULL,
                'rash_remarks' => ($r->rash_yn) ? $r->rash_remarks : NULL,
                'cough' => ($r->cough_yn) ? 1 : 0,
                'cough_onset' => ($r->cough_yn) ? $r->cough_onset : NULL,
                'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
                'colds' => ($r->colds_yn) ? 1 : 0,
                'colds_onset' => ($r->colds_yn) ? $r->colds_onset : NULL,
                'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
                'conjunctivitis' => ($r->conjunctivitis_yn) ? 1 : 0,
                'conjunctivitis_onset' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_onset : NULL,
                'conjunctivitis_remarks' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_remarks : NULL,
                'mouthsore' => ($r->mouthsore_yn) ? 1 : 0,
                'mouthsore_onset' => ($r->mouthsore_yn) ? $r->mouthsore_onset : NULL,
                'mouthsore_remarks' => ($r->mouthsore_yn) ? $r->mouthsore_remarks : NULL,
                'sorethroat' => ($r->sorethroat_yn) ? 1 : 0,
                'sorethroat_onset' => ($r->sorethroat_yn) ? $r->sorethroat_onset : NULL,
                'sorethroat_remarks' => ($r->sorethroat_yn) ? $r->sorethroat_remarks : NULL,
                'lossoftaste' => ($r->lossoftaste_yn) ? 1 : 0,
                'lossoftaste_onset' => ($r->lossoftaste_yn) ? $r->lossoftaste_onset : NULL,
                'lossoftaste_remarks' => ($r->lossoftaste_yn) ? $r->lossoftaste_remarks : NULL,
                'lossofsmell' => ($r->lossofsmell_yn) ? 1 : 0,
                'lossofsmell_onset' => ($r->lossofsmell_yn) ? $r->lossofsmell_onset : NULL,
                'lossofsmell_remarks' => ($r->lossofsmell_yn) ? $r->lossofsmell_remarks : NULL,
                'headache' => ($r->headache_yn) ? 1 : 0,
                'headache_onset' => ($r->headache_yn) ? $r->headache_onset : NULL,
                'headache_remarks' => ($r->headache_yn) ? $r->headache_remarks : NULL,
                'jointpain' => ($r->jointpain_yn) ? 1 : 0,
                'jointpain_onset' => ($r->jointpain_yn) ? $r->jointpain_onset : NULL,
                'jointpain_remarks' => ($r->jointpain_yn) ? $r->jointpain_remarks : NULL,
                'musclepain' => ($r->musclepain_yn) ? 1 : 0,
                'musclepain_onset' => ($r->musclepain_yn) ? $r->musclepain_onset : NULL,
                'musclepain_remarks' => ($r->musclepain_yn) ? $r->musclepain_remarks : NULL,
                'diarrhea' => ($r->diarrhea_yn) ? 1 : 0,
                'bloody_stool' => ($r->diarrhea_yn && $r->bloody_stool) ? 1 : 0,
                'diarrhea_onset' => ($r->diarrhea_yn) ? $r->diarrhea_onset : NULL,
                'diarrhea_remarks' => ($r->diarrhea_yn) ? $r->diarrhea_remarks : NULL,
                'abdominalpain' => ($r->abdominalpain_yn) ? 1 : 0,
                'abdominalpain_onset' => ($r->abdominalpain_yn) ? $r->abdominalpain_onset : NULL,
                'abdominalpain_remarks' => ($r->abdominalpain_yn) ? $r->abdominalpain_remarks : NULL,
                'vomiting' => ($r->vomiting_yn) ? 1 : 0,
                'vomiting_onset' => ($r->vomiting_yn) ? $r->vomiting_onset : NULL,
                'vomiting_remarks' => ($r->vomiting_yn) ? $r->vomiting_remarks : NULL,
                'weaknessofextremities' => ($r->weaknessofextremities_yn) ? 1 : 0,
                'weaknessofextremities_onset' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_onset : NULL,
                'weaknessofextremities_remarks' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_remarks : NULL,
                'paralysis' => ($r->paralysis_yn) ? 1 : 0,
                'paralysis_onset' => ($r->paralysis_yn) ? $r->paralysis_onset : NULL,
                'paralysis_remarks' => ($r->paralysis_yn) ? $r->paralysis_remarks : NULL,
                'alteredmentalstatus' => ($r->alteredmentalstatus_yn) ? 1 : 0,
                'alteredmentalstatus_onset' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_onset : NULL,
                'alteredmentalstatus_remarks' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_remarks : NULL,
                'animalbite' => ($r->animalbite_yn) ? 1 : 0,
                'animalbite_onset' => ($r->animalbite_yn) ? $r->animalbite_onset : NULL,
                'animalbite_remarks' => ($r->animalbite_yn) ? $r->animalbite_remarks : NULL,
                'anorexia' => ($r->anorexia_yn) ? 1 : 0,
                'anorexia_onset' => ($r->anorexia_yn) ? $r->anorexia_onset : NULL,
                'anorexia_remarks' => ($r->anorexia_yn) ? $r->anorexia_remarks : NULL,
                'jaundice' => ($r->jaundice_yn) ? 1 : 0,
                'jaundice_onset' => ($r->jaundice_yn) ? $r->jaundice_onset : NULL,
                'jaundice_remarks' => ($r->jaundice_yn) ? $r->jaundice_remarks : NULL,
                'nausea' => ($r->nausea_yn) ? 1 : 0,
                'nausea_onset' => ($r->nausea_yn) ? $r->nausea_onset : NULL,
                'nausea_remarks' => ($r->nausea_yn) ? $r->nausea_remarks : NULL,
                'fatigue' => ($r->fatigue_yn) ? 1 : 0,
                'fatigue_onset' => ($r->fatigue_yn) ? $r->fatigue_onset : NULL,
                'fatigue_remarks' => ($r->fatigue_yn) ? $r->fatigue_remarks : NULL,
                'dyspnea' => ($r->dyspnea_yn) ? 1 : 0,
                'dyspnea_onset' => ($r->dyspnea_yn) ? $r->dyspnea_onset : NULL,
                'dyspnea_remarks' => ($r->dyspnea_yn) ? $r->dyspnea_remarks : NULL,
                'other_symptoms' => ($r->other_symptoms_yn) ? 1 : 0,
                'other_symptoms_onset' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset : NULL,
                'other_symptoms_onset_remarks' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset_remarks : NULL,

                'is_hospitalized' => ($r->is_hospitalized == 'Y') ? 1 : 0,
                'date_admitted' => ($r->is_hospitalized == 'Y') ? $r->date_admitted : NULL,
                'date_released' => ($r->is_hospitalized == 'Y') ? $r->date_released : NULL,

                'outcome' => $r->outcome,
                'outcome_recovered_date' => ($r->outcome == 'RECOVERED') ? $r->outcome_recovered_date : NULL,
                'outcome_died_date' => ($r->outcome == 'DIED') ? $r->outcome_died_date : NULL,

                //'bigmessage' => $r->bigmessage,
                'diagnosis_type' => $r->diagnosis_type,
                'dcnote_assessment' => ($r->filled('dcnote_assessment')) ? mb_strtoupper($r->dcnote_assessment) : NULL,
                'main_diagnosis' => ($r->filled('main_diagnosis')) ? implode('|', $r->main_diagnosis) : NULL,
                'dcnote_plan' => ($r->filled('dcnote_plan')) ? mb_strtoupper($r->dcnote_plan) : NULL,
                'dcnote_diagprocedure' => ($r->filled('dcnote_diagprocedure')) ? mb_strtoupper($r->dcnote_diagprocedure) : NULL,
                //'other_diagnosis' => ($r->filled('other_diagnosis')) ? implode(',', $r->other_diagnosis) : NULL,
                //'rx' => ($r->filled('rx')) ? mb_strtoupper($r->rx) : NULL,
                'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,

                'comorbid_list' => ($r->filled('comorbid_list')) ? implode(',', $r->comorbid_list) : NULL,
                'firstdegree_comorbid_list' => ($r->filled('firstdegree_comorbid_list')) ? implode(',', $r->firstdegree_comorbid_list) : NULL,

                'alert_list' => ($r->filled('alert_list')) ? implode(',', $r->alert_list) : NULL,
                'alert_ifdisability_list' => ($r->filled('alert_ifdisability_list') && in_array('DISABILITY', $r->alert_list)) ? implode(',', $r->alert_ifdisability_list) : NULL,
                'alert_description' => ($r->filled('alert_description')) ? mb_strtoupper($r->alert_description) : NULL,

                'laboratory_request_list' => ($r->filled('laboratory_request_list')) ? implode(',', $r->laboratory_request_list) : NULL,
                'imaging_request_list' => ($r->filled('imaging_request_list')) ? implode(',', $r->imaging_request_list) : NULL,
                
                //'status' => 'approved',
                'name_of_physician' => $r->name_of_physician,
                'other_doctor' => ($r->name_of_physician == 'OTHERS') ? mb_strtoupper($r->other_doctor) : NULL,
                'dru_name' => ($r->name_of_physician != 'OTHERS') ? SyndromicDoctor::where('doctor_name', $r->name_of_physician)->first()->dru_name : NULL,
                
                'updated_by' => auth()->user()->id,
            ];

            if($d->facility_id == 11730) { //Manggahan Facility ID Checking
                $fid_check = SyndromicRecords::where('id', '!=', $record_id)
                ->where('facility_controlnumber', $r->facility_controlnumber)->first();
    
                if($fid_check) {
                    return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Error: Facility Control Number was already used to other record. Please double check and try again.')
                    ->with('msgtype', 'danger');
                }
                else {
                    $values_array = $values_array + [
                        'facility_controlnumber' => $r->facility_controlnumber,
                    ];
                }
            }

            if($d->isHospitalRecord()) {
                $values_array = $values_array + [
                    'hospital_completion' => 'PART2',
                    'hosp_identifier' => $r->hosp_identifier,
                    'o2sat' => $r->o2sat,

                    'is_pregnant' => ($r->is_pregnant == 'Y') ? 1 : 0,
                    'lmp' => ($r->is_pregnant == 'Y') ? $r->lmp : NULL,
                    'edc' => ($r->is_pregnant == 'Y') ? $r->edc : NULL,

                    'procedure_done' => $r->procedure_done,
                    'disposition' => $r->disposition,

                    'is_discharged' => $r->is_discharged,
                    'date_discharged' => ($r->is_discharged == 'Y') ? $r->date_discharged : NULL,

                    'tags' => $r->tags,
                    'created_by' => ($r->hospital_completion == 'PART1') ? auth()->user()->id : $d->created_by,
                ];
            }

            $u = SyndromicRecords::where('id', $d->id)
            ->update($values_array);

            //UPDATE SUSPECTED DISEASE LIST BASED ON NEW SYMPTOMS
            $fetch_record = SyndromicRecords::find($d->id);
            $fetch_record->generated_susdiseaselist = ($fetch_record->getListOfSuspDiseases() != 'N/A') ? $fetch_record->getListOfSuspDiseases() : NULL;
            if($fetch_record->isDirty()) {
                $fetch_record->save();
            }

            $msg = 'Record was updated successfully';
        }
        else if($r->submit == "verify_cesu") {
            if($d->cesu_verified == 0) {
                if(in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) {
                    $d->cesu_verified = 1;
                    $d->cesu_verified_date = date('Y-m-d H:i:s');
                    $d->cesu_verified_by = auth()->user()->id;

                    $d->save();
                }
            }

            $msg = 'Record was marked verified by CESU successfully';
        }
        else if($r->submit == "verify_brgy") {

            if($d->brgy_verified == 0) {
                if(in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) {
                    $d->brgy_verified = 1;
                    $d->brgy_verified_date = date('Y-m-d H:i:s');
                    $d->brgy_verified_by = auth()->user()->id;
    
                    $d->save();
                }
            }

            $msg = 'Record was marked verified by Barangay successfully';
        }

        return redirect()->back()
        ->with('msg', $msg)
        ->with('msgtype', 'success');
    }

    public function deleteRecord($record_id) {
        $d = SyndromicRecords::findOrFail($record_id);

        if($d->hasPermissionToDelete()) {
            $d->delete();
            
            return redirect()->route('syndromic_home')
            ->with('msg', 'Record associated with the patient was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function downloadItrDocx($record_id) {
        $d = SyndromicRecords::findOrFail($record_id);

        $paylname = 'ITR_'.$d->syndromic_patient->lname.'_'.date('mdY', strtotime($d->created_at)).'.docx';

        $templateProcessor  = new TemplateProcessor(storage_path('CHO_ITR.docx'));
        
        //$templateProcessor->setValue('asd', '');
        $templateProcessor->setValue('opdno', $d->opdno);
        $templateProcessor->setValue('qcode', $d->opdno);
        $templateProcessor->setValue('lineno', $d->line_number);

        $templateProcessor->setValue('last_name', $d->syndromic_patient->lname);
        $templateProcessor->setValue('first_name', $d->syndromic_patient->fname);
        $templateProcessor->setValue('middle_name', ($d->syndromic_patient->mname) ? $d->syndromic_patient->mname : 'N/A');
        $templateProcessor->setValue('suffix', ($d->syndromic_patient->suffix) ? $d->syndromic_patient->suffix : 'N/A');
        $templateProcessor->setValue('complete_address', $d->syndromic_patient->getStreetPurok());
        $templateProcessor->setValue('barangay', $d->syndromic_patient->address_brgy_text);
        $templateProcessor->setValue('city', $d->syndromic_patient->address_muncity_text);
        $templateProcessor->setValue('bdate', date('m/d/Y', strtotime($d->syndromic_patient->bdate)));
        $templateProcessor->setValue('age', $d->syndromic_patient->getAge());
        $templateProcessor->setValue('sex', substr($d->syndromic_patient->gender,0,1));
        $templateProcessor->setValue('cs', $d->syndromic_patient->cs);
        $templateProcessor->setValue('get_contactno', $d->syndromic_patient->getContactNumber());
        $templateProcessor->setValue('philhealth', ($d->syndromic_patient->philhealth) ? $d->syndromic_patient->philhealth : 'N/A');
        $templateProcessor->setValue('email', ($d->syndromic_patient->email) ? $d->syndromic_patient->email : 'N/A');
        $templateProcessor->setValue('mother_name', ($d->syndromic_patient->mother_name) ? $d->syndromic_patient->mother_name : 'N/A');
        $templateProcessor->setValue('father_name', ($d->syndromic_patient->father_name) ? $d->syndromic_patient->father_name : 'N/A');
        $templateProcessor->setValue('spouse_name', ($d->syndromic_patient->spouse_name) ? $d->syndromic_patient->spouse_name : 'N/A');
        $templateProcessor->setValue('minor_guardian', ($d->syndromic_patient->ifminor_resperson) ? $d->syndromic_patient->ifminor_resperson : 'N/A');
        $templateProcessor->setValue('guardian_res', ($d->syndromic_patient->ifminor_resrelation) ? $d->syndromic_patient->ifminor_resrelation : 'N/A');
        
        $templateProcessor->setValue('chief_complain', $d->chief_complain);
        $templateProcessor->setValue('con_date', date('m/d/Y h:i A', strtotime($d->consultation_date)));
        $templateProcessor->setValue('temp', $d->temperature.'°C');
        $templateProcessor->setValue('bp', ($d->bloodpressure) ? $d->bloodpressure : 'N/A');
        $templateProcessor->setValue('height', ($d->height) ? $d->height.'cm' : 'N/A');
        $templateProcessor->setValue('weight', ($d->weight) ? $d->weight.'kg' : 'N/A');
        $templateProcessor->setValue('rr', ($d->respiratoryrate) ? $d->respiratoryrate : 'N/A');
        $templateProcessor->setValue('pulse', ($d->pulserate) ? $d->pulserate : 'N/A');
        
        $templateProcessor->setValue('list_assessment', ($d->dcnote_assessment) ? $d->dcnote_assessment : '');
        $templateProcessor->setValue('list_plan', ($d->dcnote_plan) ? $d->dcnote_plan : '');
        $templateProcessor->setValue('rx', ($d->rx) ? $d->rx : '');
        $templateProcessor->setValue('list_diag', ($d->dcnote_diagprocedure) ? $d->dcnote_diagprocedure : '');

        /*
        $templateProcessor->setValue('abdoons', ($d->abdominalpain_onset) ? date('m/d/Y', strtotime($d->abdominalpain_onset)) : 'N/A');
        $templateProcessor->setValue('amsons', ($d->alteredmentalstatus_onset) ? date('m/d/Y', strtotime($d->alteredmentalstatus_onset)) : 'N/A');
        $templateProcessor->setValue('abtons', ($d->animalbite_onset) ? date('m/d/Y', strtotime($d->animalbite_onset)) : 'N/A');
        $templateProcessor->setValue('couons', ($d->cough_onset) ? date('m/d/Y', strtotime($d->cough_onset)) : 'N/A');
        $templateProcessor->setValue('colons', ($d->colds_onset) ? date('m/d/Y', strtotime($d->colds_onset)) : 'N/A');
        $templateProcessor->setValue('conons', ($d->conjunctivitis_onset) ? date('m/d/Y', strtotime($d->conjunctivitis_onset)) : 'N/A');
        $templateProcessor->setValue('eatons', ($d->anorexia_onset) ? date('m/d/Y', strtotime($d->anorexia_onset)) : 'N/A');
        $templateProcessor->setValue('fatons', ($d->fatigue_onset) ? date('m/d/Y', strtotime($d->fatigue_onset)) : 'N/A');
        $templateProcessor->setValue('fevons', ($d->fever_onset) ? date('m/d/Y', strtotime($d->fever_onset)) : 'N/A');
        $templateProcessor->setValue('hedons', ($d->headache_onset) ? date('m/d/Y', strtotime($d->headache_onset)) : 'N/A');
        $templateProcessor->setValue('joions', ($d->jointpain_onset) ? date('m/d/Y', strtotime($d->jointpain_onset)) : 'N/A');
        $templateProcessor->setValue('jauons', ($d->jaundice_onset) ? date('m/d/Y', strtotime($d->jaundice_onset)) : 'N/A');
        $templateProcessor->setValue('losons', ($d->lossofsmell_onset) ? date('m/d/Y', strtotime($d->lossofsmell_onset)) : 'N/A');
        $templateProcessor->setValue('lotons', ($d->lossoftaste_onset) ? date('m/d/Y', strtotime($d->lossoftaste_onset)) : 'N/A');
        $templateProcessor->setValue('musons', ($d->musclepain_onset) ? date('m/d/Y', strtotime($d->musclepain_onset)) : 'N/A');
        $templateProcessor->setValue('nauons', ($d->nausea_onset) ? date('m/d/Y', strtotime($d->nausea_onset)) : 'N/A');
        $templateProcessor->setValue('parons', ($d->paralysis_onset) ? date('m/d/Y', strtotime($d->paralysis_onset)) : 'N/A');
        $templateProcessor->setValue('rasons', ($d->rash_onset) ? date('m/d/Y', strtotime($d->rash_onset)) : 'N/A');
        $templateProcessor->setValue('sormons', ($d->mouthsore_onset) ? date('m/d/Y', strtotime($d->mouthsore_onset)) : 'N/A');
        $templateProcessor->setValue('sortons', ($d->sorethroat_onset) ? date('m/d/Y', strtotime($d->sorethroat_onset)) : 'N/A');
        $templateProcessor->setValue('shoons', ($d->dyspnea_onset) ? date('m/d/Y', strtotime($d->dyspnea_onset)) : 'N/A');
        $templateProcessor->setValue('vomons', ($d->vomiting_onset) ? date('m/d/Y', strtotime($d->vomiting_onset)) : 'N/A');
        $templateProcessor->setValue('weaons', ($d->weaknessofextremities_onset) ? date('m/d/Y', strtotime($d->weaknessofextremities_onset)) : 'N/A');
        $templateProcessor->setValue('othons', ($d->other_symptoms_onset) ? date('m/d/Y', strtotime($d->other_symptoms_onset)) : 'N/A');

        $templateProcessor->setValue('s1c', ($d->abdominalpain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s2c', ($d->alteredmentalstatus == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s3c', ($d->animalbite == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s4c', ($d->cough == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s5c', ($d->colds == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s6c', ($d->conjunctivitis == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s7c', ($d->anorexia == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s8c', ($d->fatigue == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s9c', ($d->fever == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s10c', ($d->headache == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s11c', ($d->jointpain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s12c', ($d->jaundice == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s13c', ($d->lossofsmell == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s14c', ($d->lossoftaste == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s15c', ($d->musclepain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s16c', ($d->nausea == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s17c', ($d->paralysis == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s18c', ($d->rash == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s19c', ($d->mouthsore == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s20c', ($d->sorethroat == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s21c', ($d->dyspnea == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s22c', ($d->vomiting == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s23c', ($d->weaknessofextremities == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s24c', ($d->other_symptoms == 1) ? '☑' : '☐');
        */

        $templateProcessor->setValue('doctor_name', $d->name_of_physician);
        $templateProcessor->setValue('doctor_position', $d->getPhysicianDetails()->position);
        $templateProcessor->setValue('doctor_regno', $d->getPhysicianDetails()->reg_no);

        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="'. urlencode($paylname).'"');
        $templateProcessor->saveAs('php://output');
    }

    public function generateMedCert($record_id, Request $r) {
        $d = SyndromicRecords::findOrFail($record_id);
        
        $d->medcert_enabled = 1;
        $d->medcert_generated_date = $r->medcert_generated_date;
        $d->medcert_validity_date = $r->medcert_validity_date;
        $d->medcert_purpose = ($r->filled('medcert_purpose')) ? mb_strtoupper($r->medcert_purpose) : NULL;
        //$d->outcome = 'RECOVERED';
        //$d->outcome_recovered_date = $r->medcert_validity_date;

        if($r->filled('medcert_start_date') && $r->filled('medcert_end_date')) {
            $d->medcert_start_date = $r->medcert_start_date;
            $d->medcert_end_date = $r->medcert_end_date;
        }

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->route('syndromic_view_medcert', $d->id);
    }

    public function viewMedCert($record_id) {
        $d = SyndromicRecords::findOrFail($record_id);

        if($d->medcert_enabled == 0) {
            $d->medcert_enabled = 1;
            $d->medcert_generated_date = date('Y-m-d');
            $d->medcert_validity_date = date('Y-m-d');
            //$d->outcome = 'RECOVERED';
            //$d->outcome_recovered_date = date('Y-m-d');

            if($d->isDirty()) {
                $d->save();
            }
        }

        if(!is_null($d->getPhysicianDetails())) {
            return view('syndromic.view_medcert', ['d' => $d]);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Please specify Physician before generating MedCert')
            ->with('msgtype', 'warning');
        }
    }

    public function medcertOnlineVerify($qr) {
        $d = SyndromicRecords::where('qr', $qr)
        ->where('medcert_enabled', 1)
        ->first();

        return view('syndromic.online_medcert', ['c' => $d]);
    }

    public function addLaboratoryData($id, $case_code) {
        $d = SyndromicRecords::findOrFail($id);

        $rmt_list = Employee::getMedtechList();

        return view('syndromic.laboratory.add_laboratory_body_main', [
            'case_code' => $case_code,
            'd' => $d,
            'rmt_list' => $rmt_list,
        ]);
    }

    public function storeLaboratoryData($id, $case_code,  Request $r) {
        if($case_code == 'Dengue') {

        }
        else {
            
        }
    }

    public function diseasemap() {
        //fetch brgy
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();
        
        $final_arr = [];

        foreach($brgy_list as $b) {
            $case_now = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereDate('created_at', date('Y-m-d'))
            ->count();

            $case_month = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereMonth('created_at', date('m'))
            ->count();

            $case_year = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereYear('created_at', date('Y'))
            ->count();

            $final_arr[] = [
                'brgy' => $b->brgyName,
                'brgy_id' => $b->id,
                'case_now' => $case_now,
                'case_month' => $case_month,
                'case_year' => $case_year,
            ];
        }
        
        return view('syndromic.mapdashboard', [
            'list' => $final_arr,
        ]);
    }

    public function viewDiseaseList() {
        if(request()->input('brgy_id') && request()->input('type')) {
            $brgy_id = request()->input('brgy_id');
            $type = request()->input('type');

            $b = Brgy::findOrFail($brgy_id);
            
            $query = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            });

            if($type == 'daily') {
                $query = $query->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
            }
            else if($type == 'monthly') {
                $query = $query->whereMonth('created_at', date('m'))->orderBy('created_at', 'DESC')->get();
            }
            else if($type == 'yearly') {
                $query = $query->whereYear('created_at', date('Y'))->orderBy('created_at', 'DESC')->get();
            }

            return view('syndromic.map_disease_list', [
                'list' => $query,
                'type' => $type,
                'b' => $b,
            ]);
        }
        else {

        }
    }

    public function walkin_part1() {
        
    }

    public function walkin_part2() {

    }

    public function walkin_part3(Request $r) {

    }

    public function icd10list(Request $request) {
        $list = [];
        if($request->has('q') && strlen($request->input('q')) > 1) {
            $s = mb_strtoupper($request->q);
            
            $data = Icd10Code::where('ICD10_CODE', 'LIKE', "%".$s."%")
            ->orWhere('ICD10_DESC', 'LIKE', "%".$s."%")
            ->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->ICD10_CODE,
                    'text' => $item->ICD10_CODE.' - '.$item->ICD10_DESC,
                    'desc' => $item->ICD10_CODE.'; '.$item->ICD10_DESC,
                ]);
            }
        }

        return response()->json($list);
    }

    public function pharmacyMedsList(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $s = mb_strtoupper($request->q);
            
            $data = PharmacySupplySub::where('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)
            ->whereHas('pharmacysupplymaster', function ($q) use ($s) {
                $q->where('name', 'LIKE', "%".$s."%");
            })
            ->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => $item->pharmacysupplymaster->name,
                ]);
            }
        }

        return response()->json($list);
    }

    public function diseaseCheckerMain() {
        $sel_db = request()->input('db');
        $year = request()->input('year');
        $mw = request()->input('mw');

        $abd_count = 0;
        $aefi_count = 0;
        $aes_count = 0;
        $afp_count = 0;
        $ahf_count = 0;
        $ames_count = 0;
        $anthrax_count = 0;
        $chikv_count = 0;
        $cholera_count = 0;
        $dengue_count = 0;
        $diph_count = 0;
        $hepatitis_count = 0;
        $hfmd_count = 0;
        $influenza_count = 0;
        $leptospirosis_count = 0;
        $malaria_count = 0;
        $measles_count = 0;
        $meningitis_count = 0;
        $meningo_count = 0;
        $nnt_count = 0;
        $nt_count = 0;
        $pert_count = 0;
        $psp_count = 0;
        $rabies_count = 0;
        $rotavirus_count = 0;
        $typhoid_count = 0;
        
        $covid_count = 0;

        if($sel_db && $year) {
            if($sel_db == 'OPD') {
                $route = 'syndromic_diseasechecker_specific';

                $abd_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Acute Bloody Diarrhea (ABD)', generated_susdiseaselist)");

                $aefi_count = 0;

                $hfmd_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('HFMD', generated_susdiseaselist)");

                $influenza_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Influenza-like Illness (ILI)', generated_susdiseaselist)");

                $leptospirosis_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Leptospirosis', generated_susdiseaselist)");

                /*
                $leptospirosis_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Leptospirosis', generated_susdiseaselist)");
                */
                
                $aes_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Acute Encephalitis', generated_susdiseaselist)");
                
                $afp_count = SyndromicRecords::whereYear('created_at', $year)
                ->whereRaw("FIND_IN_SET('Acute Flaccid Paralysis', generated_susdiseaselist)");

                if($mw) {
                    $abd_count = $abd_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                    $hfmd_count = $hfmd_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                    $influenza_count = $influenza_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                    $leptospirosis_count = $leptospirosis_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                    $aes_count = $aes_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                    $afp_count = $afp_count->whereRaw('WEEK(created_at) = ' . $mw)->count();
                }
                else {
                    $abd_count = $abd_count->count();
                    $hfmd_count = $hfmd_count->count();
                    $influenza_count = $influenza_count->count();
                    $leptospirosis_count = $leptospirosis_count->count();
                    $aes_count = $aes_count->count();
                    $afp_count = $afp_count->count();
                }

                
            }
            else {
                $route = 'pidsr.casechecker';
                
                $abd_count = Abd::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $aefi_count = Aefi::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $aes_count = Aes::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $afp_count = Afp::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $ahf_count = Ahf::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $ames_count = Ames::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $anthrax_count = Anthrax::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $chikv_count = Chikv::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $cholera_count = Cholera::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $dengue_count = Dengue::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $diph_count = Diph::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $hepatitis_count = Hepatitis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $hfmd_count = Hfmd::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $influenza_count = Influenza::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $leptospirosis_count = Leptospirosis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $malaria_count = Malaria::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $measles_count = Measles::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $meningitis_count = Meningitis::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $meningo_count = Meningo::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $nnt_count = Nnt::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $nt_count = Nt::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $pert_count = Pert::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $psp_count = Psp::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $rabies_count = Rabies::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $rotavirus_count = Rotavirus::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                $typhoid_count = Typhoid::where('Year', $year)->where('enabled', 1)->where('match_casedef', 1);
                
                //$covid_count = Aefi::where('Year', $year)->where('enabled', 1);

                if($mw) {
                    $abd_count = $abd_count->where('encoded_mw', $mw)->count();
                    $aefi_count = $aefi_count->where('encoded_mw', $mw)->count();
                    $aes_count = $aes_count->where('encoded_mw', $mw)->count();
                    $afp_count = $afp_count->where('encoded_mw', $mw)->count();
                    $ahf_count = $ahf_count->where('encoded_mw', $mw)->count();
                    $ames_count = $ames_count->where('encoded_mw', $mw)->count();
                    $anthrax_count = $anthrax_count->where('encoded_mw', $mw)->count();
                    $chikv_count = $chikv_count->where('encoded_mw', $mw)->count();
                    $cholera_count = $cholera_count->where('encoded_mw', $mw)->count();
                    $dengue_count = $dengue_count->where('encoded_mw', $mw)->count();
                    $diph_count = $diph_count->where('encoded_mw', $mw)->count();
                    $hepatitis_count = $hepatitis_count->where('encoded_mw', $mw)->count();
                    $hfmd_count = $hfmd_count->where('encoded_mw', $mw)->count();
                    $influenza_count = $influenza_count->where('encoded_mw', $mw)->count();
                    $leptospirosis_count = $leptospirosis_count->where('encoded_mw', $mw)->count();
                    $malaria_count = $malaria_count->where('encoded_mw', $mw)->count();
                    $measles_count = $measles_count->where('encoded_mw', $mw)->count();
                    $meningitis_count = $meningitis_count->where('encoded_mw', $mw)->count();
                    $meningo_count = $meningo_count->where('encoded_mw', $mw)->count();
                    $nnt_count = $nnt_count->where('encoded_mw', $mw)->count();
                    $nt_count = $nt_count->where('encoded_mw', $mw)->count();
                    $pert_count = $pert_count->where('encoded_mw', $mw)->count();
                    $psp_count = $psp_count->where('encoded_mw', $mw)->count();
                    $rabies_count = $rabies_count->where('encoded_mw', $mw)->count();
                    $rotavirus_count = $rotavirus_count->where('encoded_mw', $mw)->count();
                    $typhoid_count = $typhoid_count->where('encoded_mw', $mw)->count();
                }
                else {
                    $abd_count = $abd_count->count();
                    $aefi_count = $aefi_count->count();
                    $aes_count = $aes_count->count();
                    $afp_count = $afp_count->count();
                    $ahf_count = $ahf_count->count();
                    $ames_count = $ames_count->count();
                    $anthrax_count = $anthrax_count->count();
                    $chikv_count = $chikv_count->count();
                    $cholera_count = $cholera_count->count();
                    $dengue_count = $dengue_count->count();
                    $diph_count = $diph_count->count();
                    $hepatitis_count = $hepatitis_count->count();
                    $hfmd_count = $hfmd_count->count();
                    $influenza_count = $influenza_count->count();
                    $leptospirosis_count = $leptospirosis_count->count();
                    $malaria_count = $malaria_count->count();
                    $measles_count = $measles_count->count();
                    $meningitis_count = $meningitis_count->count();
                    $meningo_count = $meningo_count->count();
                    $nnt_count = $nnt_count->count();
                    $nt_count = $nt_count->count();
                    $pert_count = $pert_count->count();
                    $psp_count = $psp_count->count();
                    $rabies_count = $rabies_count->count();
                    $rotavirus_count = $rotavirus_count->count();
                    $typhoid_count = $typhoid_count->count();
                }
            }
        }

        return view('syndromic.disease_checker', [
            'abd_count' => $abd_count,
            'aefi_count' => $aefi_count,
            'aes_count' => $aes_count,
            'afp_count' => $afp_count,
            'ahf_count' => $ahf_count,
            'ames_count' => $ames_count,
            'anthrax_count' => $anthrax_count,
            'chikv_count' => $chikv_count,
            'cholera_count' => $cholera_count,
            'dengue_count' => $dengue_count,
            'diph_count' => $diph_count,
            'hepatitis_count' => $hepatitis_count,
            'hfmd_count' => $hfmd_count,
            'influenza_count' => $influenza_count,
            'leptospirosis_count' => $leptospirosis_count,
            'malaria_count' => $malaria_count,
            'measles_count' => $measles_count,
            'meningitis_count' => $meningitis_count,
            'meningo_count' => $meningo_count,
            'nnt_count' => $nnt_count,
            'nt_count' => $nt_count,
            'pert_count' => $pert_count,
            'psp_count' => $psp_count,
            'rabies_count' => $rabies_count,
            'rotavirus_count' => $rotavirus_count,
            'typhoid_count' => $typhoid_count,
            
            'covid_count' => $covid_count,
            'route' => $route,
        ]);
    }

    public function diseaseCheckerList() {

    }

    public function hospDailyReport() {
        if(request()->input('d')) {
            $sdate = request()->input('d');
        }
        else {
            $sdate = date('Y-m-d');
        }

        $opd_old = SyndromicRecords::whereDate('consultation_date', $sdate);
        $opd_new = SyndromicRecords::whereDate('consultation_date', $sdate);
        $opd_police = SyndromicRecords::whereDate('consultation_date', $sdate);
        $opd_thoc = SyndromicRecords::whereDate('consultation_date', $sdate);

        $er_old = SyndromicRecords::whereDate('consultation_date', $sdate);
        $er_new = SyndromicRecords::whereDate('consultation_date', $sdate);
        $er_police = SyndromicRecords::whereDate('consultation_date', $sdate);
        $er_thoc = SyndromicRecords::whereDate('consultation_date', $sdate);

        $admission = SyndromicRecords::whereDate('consultation_date', $sdate);
        //$inpatient = SyndromicRecords::whereDate('date_discharged', $sdate);
        $discharged = SyndromicRecords::whereDate('consultation_date', $sdate);
        $doa = SyndromicRecords::whereDate('consultation_date', $sdate);

        $opd_old = $opd_old->where('nature_of_visit', 'FOLLOW-UP VISIT')
        ->where('hosp_identifier', 'OPD')
        ->count();

        $opd_new = $opd_new->where('nature_of_visit', 'NEW CONSULTATION/CASE')
        ->where('hosp_identifier', 'OPD')
        ->count();

        $opd_police = $opd_police->where('disposition', 'SENT TO JAIL')
        ->where('hosp_identifier', 'OPD')
        ->count();

        $opd_thoc = $opd_thoc->where('disposition', 'THOC')
        ->where('hosp_identifier', 'OPD')
        ->count();

        $er_old = $er_old->where('nature_of_visit', 'FOLLOW-UP VISIT')
        ->where('hosp_identifier', 'ER')
        ->count();

        $er_new = $er_new->where('nature_of_visit', 'NEW CONSULTATION/CASE')
        ->where('hosp_identifier', 'ER')
        ->count();

        $er_police = $er_police->where('disposition', 'SENT TO JAIL')
        ->where('hosp_identifier', 'ER')
        ->count();

        $er_thoc = $er_thoc->where('disposition', 'THOC')
        ->where('hosp_identifier', 'ER')
        ->count();

        $inpatient = SyndromicRecords::where('disposition', 'ADMITTED')
        ->where('is_discharged', 'N')
        ->count();

        $admission = $admission->where('disposition', 'ADMITTED')
        ->where('is_discharged', 'N')
        ->count();

        $discharged = $discharged->where('disposition', 'ADMITTED')
        ->where('is_discharged', 'Y')
        ->count();

        $doa = $doa->where('outcome', 'DOA')
        ->count();

        return view('syndromic.hospital.daily_opd', [
            'opd_old' => $opd_old,
            'opd_new' => $opd_new,
            'opd_police' => $opd_police,
            'opd_thoc' => $opd_thoc,

            'er_old' => $er_old,
            'er_new' => $er_new,
            'er_police' => $er_police,
            'er_thoc' => $er_thoc,

            'inpatient' => $inpatient,
            'admission' => $admission,
            'discharged' => $discharged,
            'doa' => $doa,
        ]);
    }

    public function hospSummaryReport() {
        ini_set('max_execution_time', 9999999);

        if(request()->input('smonth') && request()->input('syear')) {
            $smonth = request()->input('smonth');
            $syear = request()->input('syear');
        }
        else {
            $smonth = date('m');
            $syear = date('Y');
        }

        $date = Carbon::createFromDate($syear, $smonth, 1);
        $month_flavor = $date->format('F').' 1 - '.$date->format('t');

        if(request()->input('id')) {
            $id = request()->input('id');

            if($id != 'OPD' && $id != 'ER') {
                return abort(401);
            }
        }
        else {
            return abort(401);
        }

        $group_diagnosis = SyndromicRecords::where('hosp_identifier', $id)
        ->where('facility_id', auth()->user()->itr_facility_id)
        ->where('hospital_completion', 'PART2');
        
        if(request()->input('type') == 'Daily') {
            $sdate = request()->input('sdate');

            $group_diagnosis = $group_diagnosis->whereDate('consultation_date', $sdate);
        }
        else {
            $group_diagnosis = $group_diagnosis->whereMonth('consultation_date', $smonth)
            ->whereYear('consultation_date', $syear);
        }

        if($group_diagnosis->count() <= 0) {
            return 'No Results found.';
        }

        $group_diagnosis = $group_diagnosis->orderBy('dcnote_assessment', 'ASC')
        ->groupBy('dcnote_assessment')
        ->pluck('dcnote_assessment')
        ->toArray();

        $gd_final = [];

        foreach($group_diagnosis as $f) {
            $separate_arr = explode(",", $f);

            $string = $separate_arr[0];

            if(!in_array($string, $gd_final)) {
                $gd_final[] = $separate_arr[0];
            }
        }

        $final_arr = [];

        foreach($gd_final as $g) {
            $pedia_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $pedia_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $pedia_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $adult_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            if(request()->input('type') == 'Daily') {
                $sdate = request()->input('sdate');

                $pedia_old_m = $pedia_old_m->whereDate('consultation_date', $sdate);
                $pedia_new_m = $pedia_new_m->whereDate('consultation_date', $sdate);
                $pedia_police_m = $pedia_police_m->whereDate('consultation_date', $sdate);
                $pedia_old_f = $pedia_old_f->whereDate('consultation_date', $sdate);
                $pedia_new_f = $pedia_new_f->whereDate('consultation_date', $sdate);
                $pedia_police_f = $pedia_police_f->whereDate('consultation_date', $sdate);

                $adult_old_m = $adult_old_m->whereDate('consultation_date', $sdate);
                $adult_new_m = $adult_new_m->whereDate('consultation_date', $sdate);
                $adult_police_m = $adult_police_m->whereDate('consultation_date', $sdate);
                $adult_old_f = $adult_old_f->whereDate('consultation_date', $sdate);
                $adult_new_f = $adult_new_f->whereDate('consultation_date', $sdate);
                $adult_police_f = $adult_police_f->whereDate('consultation_date', $sdate);

                $senior_old_m = $senior_old_m->whereDate('consultation_date', $sdate);
                $senior_new_m = $senior_new_m->whereDate('consultation_date', $sdate);
                $senior_police_m = $senior_police_m->whereDate('consultation_date', $sdate);
                $senior_old_f = $senior_old_f->whereDate('consultation_date', $sdate);
                $senior_new_f = $senior_new_f->whereDate('consultation_date', $sdate);
                $senior_police_f = $senior_police_f->whereDate('consultation_date', $sdate);
            }
            else {
                $pedia_old_m = $pedia_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_new_m = $pedia_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_police_m = $pedia_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_old_f = $pedia_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_new_f = $pedia_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_police_f = $pedia_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);

                $adult_old_m = $adult_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_new_m = $adult_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_police_m = $adult_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_old_f = $adult_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_new_f = $adult_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_police_f = $adult_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);

                $senior_old_m = $senior_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_new_m = $senior_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_police_m = $senior_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_old_f = $senior_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_new_f = $senior_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_police_f = $senior_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
            }
            
            $final_arr[] = [
                'name' => $g,

                'pedia_old_m' => $pedia_old_m->count(),
                
                'pedia_new_m' => $pedia_new_m->count(),

                'pedia_police_m' => $pedia_police_m->count(),

                'pedia_old_f' => $pedia_old_f->count(),
                
                'pedia_new_f' => $pedia_new_f->count(),

                'pedia_police_f' => $pedia_police_f->count(),

                'adult_old_m' => $adult_old_m->count(),
                
                'adult_new_m' => $adult_new_m->count(),

                'adult_police_m' => $adult_police_m->count(),

                'adult_old_f' => $adult_old_f->count(),
                
                'adult_new_f' => $adult_new_f->count(),

                'adult_police_f' => $adult_police_f->count(),

                'senior_old_m' => $senior_old_m->count(),
                
                'senior_new_m' => $senior_new_m->count(),

                'senior_police_m' => $senior_police_m->count(),

                'senior_old_f' => $senior_old_f->count(),
                
                'senior_new_f' => $senior_new_f->count(),

                'senior_police_f' => $senior_police_f->count(),
            ];
        }

        $opd_master_array = [
            'MEDICAL',
            'PEDIATRICS',
            'SURGICAL',
            'OB',
            'GYNE',
            'GENITO-URINARY',
            'ORTHO',
            'ENT',
            'FAMILY PLANNING',
            'OPHTHA',
            'ANIMAL BITE',
            'MEDICO-LEGAL',
            'DERMATOLOGY',
            'DENTAL',
            'PSYCHIATRY',
            'DOA',
            'VA',
        ];

        $second_array = [];

        foreach($opd_master_array as $o) {
            $pedia_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $pedia_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $pedia_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $pedia_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $pedia_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $pedia_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('disposition', 'SENT TO JAIL');

            //ADULT

            $adult_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $adult_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $adult_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $adult_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $adult_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $adult_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('disposition', 'SENT TO JAIL');

            //SENIOR

            $senior_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $senior_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $senior_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $senior_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $senior_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $senior_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $id)
            ->where('facility_id', auth()->user()->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('disposition', 'SENT TO JAIL');

            if(request()->input('type') == 'Daily') {
                $sdate = request()->input('sdate');

                $pedia_old_m = $pedia_old_m->whereDate('consultation_date', $sdate);
                $pedia_new_m = $pedia_new_m->whereDate('consultation_date', $sdate);
                $pedia_police_m = $pedia_police_m->whereDate('consultation_date', $sdate);
                $pedia_old_f = $pedia_old_f->whereDate('consultation_date', $sdate);
                $pedia_new_f = $pedia_new_f->whereDate('consultation_date', $sdate);
                $pedia_police_f = $pedia_police_f->whereDate('consultation_date', $sdate);

                $adult_old_m = $adult_old_m->whereDate('consultation_date', $sdate);
                $adult_new_m = $adult_new_m->whereDate('consultation_date', $sdate);
                $adult_police_m = $adult_police_m->whereDate('consultation_date', $sdate);
                $adult_old_f = $adult_old_f->whereDate('consultation_date', $sdate);
                $adult_new_f = $adult_new_f->whereDate('consultation_date', $sdate);
                $adult_police_f = $adult_police_f->whereDate('consultation_date', $sdate);

                $senior_old_m = $senior_old_m->whereDate('consultation_date', $sdate);
                $senior_new_m = $senior_new_m->whereDate('consultation_date', $sdate);
                $senior_police_m = $senior_police_m->whereDate('consultation_date', $sdate);
                $senior_old_f = $senior_old_f->whereDate('consultation_date', $sdate);
                $senior_new_f = $senior_new_f->whereDate('consultation_date', $sdate);
                $senior_police_f = $senior_police_f->whereDate('consultation_date', $sdate);
            }
            else {
                $pedia_old_m = $pedia_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_new_m = $pedia_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_police_m = $pedia_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_old_f = $pedia_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_new_f = $pedia_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $pedia_police_f = $pedia_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);

                $adult_old_m = $adult_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_new_m = $adult_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_police_m = $adult_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_old_f = $adult_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_new_f = $adult_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $adult_police_f = $adult_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);

                $senior_old_m = $senior_old_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_new_m = $senior_new_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_police_m = $senior_police_m->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_old_f = $senior_old_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_new_f = $senior_new_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
                $senior_police_f = $senior_police_f->whereMonth('consultation_date', $smonth)
                ->whereYear('consultation_date', $syear);
            }
            
            if($o == 'MEDICAL') {
                $pedia_old_m = 0;
                $pedia_old_f = 0;
                $pedia_new_m = 0;
                $pedia_new_f = 0;
                $pedia_police_m = 0;
                $pedia_police_f = 0;

                /*
                $adult_old_m = $adult_old_m->where('procedure_done', 'MED CHECKUP')->count();
                $adult_old_f = $adult_old_f->where('procedure_done', 'MED CHECKUP')->count();
                $adult_new_m = $adult_new_m->where('procedure_done', 'MED CHECKUP')->count();
                $adult_new_f = $adult_new_f->where('procedure_done', 'MED CHECKUP')->count();
                $adult_police_m = $adult_police_m->where('procedure_done', 'MED CHECKUP')->count();
                $adult_police_f = $adult_police_f->where('procedure_done', 'MED CHECKUP')->count();

                $senior_old_m = $senior_old_m->where('procedure_done', 'MED CHECKUP')->count();
                $senior_old_f = $senior_old_f->where('procedure_done', 'MED CHECKUP')->count();
                $senior_new_m = $senior_new_m->where('procedure_done', 'MED CHECKUP')->count();
                $senior_new_f = $senior_new_f->where('procedure_done', 'MED CHECKUP')->count();
                $senior_police_m = $senior_police_m->where('procedure_done', 'MED CHECKUP')->count();
                $senior_police_f = $senior_police_f->where('procedure_done', 'MED CHECKUP')->count();
                */

                $adult_old_m = $adult_old_m->where('tags', 'MEDICAL')->count();
                $adult_old_f = $adult_old_f->where('tags', 'MEDICAL')->count();
                $adult_new_m = $adult_new_m->where('tags', 'MEDICAL')->count();
                $adult_new_f = $adult_new_f->where('tags', 'MEDICAL')->count();
                $adult_police_m = $adult_police_m->where('tags', 'MEDICAL')->count();
                $adult_police_f = $adult_police_f->where('tags', 'MEDICAL')->count();

                $senior_old_m = $senior_old_m->where('tags', 'MEDICAL')->count();
                $senior_old_f = $senior_old_f->where('tags', 'MEDICAL')->count();
                $senior_new_m = $senior_new_m->where('tags', 'MEDICAL')->count();
                $senior_new_f = $senior_new_f->where('tags', 'MEDICAL')->count();
                $senior_police_m = $senior_police_m->where('tags', 'MEDICAL')->count();
                $senior_police_f = $senior_police_f->where('tags', 'MEDICAL')->count();
            }
            else if($o == 'PEDIATRICS') {
                /*
                $pedia_old_m = $pedia_old_m->where('procedure_done', 'PED CHECKUP')->count();
                $pedia_old_f = $pedia_old_f->where('procedure_done', 'PED CHECKUP')->count();
                $pedia_new_m = $pedia_new_m->where('procedure_done', 'PED CHECKUP')->count();
                $pedia_new_f = $pedia_new_f->where('procedure_done', 'PED CHECKUP')->count();
                $pedia_police_m = $pedia_police_m->where('procedure_done', 'PED CHECKUP')->count();
                $pedia_police_f = $pedia_police_f->where('procedure_done', 'PED CHECKUP')->count();
                */

                $pedia_old_m = $pedia_old_m->where('tags', 'PEDIATRICS')->count();
                $pedia_old_f = $pedia_old_f->where('tags', 'PEDIATRICS')->count();
                $pedia_new_m = $pedia_new_m->where('tags', 'PEDIATRICS')->count();
                $pedia_new_f = $pedia_new_f->where('tags', 'PEDIATRICS')->count();
                $pedia_police_m = $pedia_police_m->where('tags', 'PEDIATRICS')->count();
                $pedia_police_f = $pedia_police_f->where('tags', 'PEDIATRICS')->count();

                $adult_old_m = 0;
                $adult_old_f = 0;
                $adult_new_m = 0;
                $adult_new_f = 0;
                $adult_police_m = 0;
                $adult_police_f = 0;

                $senior_old_m = 0;
                $senior_old_f = 0;
                $senior_new_m = 0;
                $senior_new_f = 0;
                $senior_police_m = 0;
                $senior_police_f = 0;
            }
            else if($o == 'MEDICO-LEGAL') {
                $pedia_old_m = $pedia_old_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $pedia_old_f = $pedia_old_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $pedia_new_m = $pedia_new_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $pedia_new_f = $pedia_new_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $pedia_police_m = $pedia_police_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $pedia_police_f = $pedia_police_f->where('procedure_done', 'MEDICO LEGAL')->count();

                $adult_old_m = $adult_old_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $adult_old_f = $adult_old_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $adult_new_m = $adult_new_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $adult_new_f = $adult_new_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $adult_police_m = $adult_police_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $adult_police_f = $adult_police_f->where('procedure_done', 'MEDICO LEGAL')->count();

                $senior_old_m = $senior_old_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $senior_old_f = $senior_old_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $senior_new_m = $senior_new_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $senior_new_f = $senior_new_f->where('procedure_done', 'MEDICO LEGAL')->count();
                $senior_police_m = $senior_police_m->where('procedure_done', 'MEDICO LEGAL')->count();
                $senior_police_f = $senior_police_f->where('procedure_done', 'MEDICO LEGAL')->count();
            }
            else if($o == 'DOA') {
                $pedia_old_m = $pedia_old_m->where('outcome', 'DOA')->count();
                $pedia_old_f = $pedia_old_f->where('outcome', 'DOA')->count();
                $pedia_new_m = $pedia_new_m->where('outcome', 'DOA')->count();
                $pedia_new_f = $pedia_new_f->where('outcome', 'DOA')->count();
                $pedia_police_m = $pedia_police_m->where('outcome', 'DOA')->count();
                $pedia_police_f = $pedia_police_f->where('outcome', 'DOA')->count();

                $adult_old_m = $adult_old_m->where('outcome', 'DOA')->count();
                $adult_old_f = $adult_old_f->where('outcome', 'DOA')->count();
                $adult_new_m = $adult_new_m->where('outcome', 'DOA')->count();
                $adult_new_f = $adult_new_f->where('outcome', 'DOA')->count();
                $adult_police_m = $adult_police_m->where('outcome', 'DOA')->count();
                $adult_police_f = $adult_police_f->where('outcome', 'DOA')->count();

                $senior_old_m = $senior_old_m->where('outcome', 'DOA')->count();
                $senior_old_f = $senior_old_f->where('outcome', 'DOA')->count();
                $senior_new_m = $senior_new_m->where('outcome', 'DOA')->count();
                $senior_new_f = $senior_new_f->where('outcome', 'DOA')->count();
                $senior_police_m = $senior_police_m->where('outcome', 'DOA')->count();
                $senior_police_f = $senior_police_f->where('outcome', 'DOA')->count();
            }
            else {
                $pedia_old_m = $pedia_old_m->where('tags', $o)->count();
                $pedia_old_f = $pedia_old_f->where('tags', $o)->count();
                $pedia_new_m = $pedia_new_m->where('tags', $o)->count();
                $pedia_new_f = $pedia_new_f->where('tags', $o)->count();
                $pedia_police_m = $pedia_police_m->where('tags', $o)->count();
                $pedia_police_f = $pedia_police_f->where('tags', $o)->count();

                $adult_old_m = $adult_old_m->where('tags', $o)->count();
                $adult_old_f = $adult_old_f->where('tags', $o)->count();
                $adult_new_m = $adult_new_m->where('tags', $o)->count();
                $adult_new_f = $adult_new_f->where('tags', $o)->count();
                $adult_police_m = $adult_police_m->where('tags', $o)->count();
                $adult_police_f = $adult_police_f->where('tags', $o)->count();

                $senior_old_m = $senior_old_m->where('tags', $o)->count();
                $senior_old_f = $senior_old_f->where('tags', $o)->count();
                $senior_new_m = $senior_new_m->where('tags', $o)->count();
                $senior_new_f = $senior_new_f->where('tags', $o)->count();
                $senior_police_m = $senior_police_m->where('tags', $o)->count();
                $senior_police_f = $senior_police_f->where('tags', $o)->count();
            }

            $second_array[] = [
                'name' => $o,
                'pedia_old_m' => $pedia_old_m,
                'pedia_old_f' => $pedia_old_f,
                'pedia_new_m' => $pedia_new_m,
                'pedia_new_f' => $pedia_new_f,
                'pedia_police_m' => $pedia_police_m,
                'pedia_police_f' => $pedia_police_f,

                'adult_old_m' => $adult_old_m,
                'adult_old_f' => $adult_old_f,
                'adult_new_m' => $adult_new_m,
                'adult_new_f' => $adult_new_f,
                'adult_police_m' => $adult_police_m,
                'adult_police_f' => $adult_police_f,

                'senior_old_m' => $senior_old_m,
                'senior_old_f' => $senior_old_f,
                'senior_new_m' => $senior_new_m,
                'senior_new_f' => $senior_new_f,
                'senior_police_m' => $senior_police_m,
                'senior_police_f' => $senior_police_f,
            ];
        }

        return view('syndromic.hospital.opd_summary', [
            'final_arr' => $final_arr,
            'second_array' => $second_array,
            'month_flavor' => $month_flavor,

            'smonth' => $smonth,
            'syear' => $syear,
        ]);
    }

    public function ChoOpdSummaryReport() {
        //SAME CODE WITH HOSPITAL SUMMARY REPORT (hospSummaryReport) but Converted to CHO Format
        ini_set('max_execution_time', 9999999);

        if(request()->input('smonth') && request()->input('syear')) {
            $smonth = request()->input('smonth');
            $syear = request()->input('syear');
        }
        else {
            $smonth = date('m');
            $syear = date('Y');
        }

        $date = Carbon::createFromDate($syear, $smonth, 1);
        $month_flavor = $date->format('F').' 1 - '.$date->format('t');

        $group_diagnosis = SyndromicRecords::where('facility_id', auth()->user()->itr_facility_id);
        
        if(request()->input('type') == 'Daily') {
            $sdate = request()->input('sdate');

            $group_diagnosis = $group_diagnosis->whereDate('consultation_date', $sdate);
        }
        else if(request()->input('type') == 'Monthly') {
            $startOfMonth = Carbon::createFromDate($syear, $smonth, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($syear, $smonth, 1)->endOfMonth();

            $group_diagnosis = $group_diagnosis->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
        }
        else if(request()->input('type') == 'Yearly') {
            $group_diagnosis = $group_diagnosis::whereYear('consultation_date', $syear);
        }
        else {
            return abort(401);
        }

        if($group_diagnosis->count() <= 0) {
            return 'No Results found.';
        }

        $group_diagnosis = $group_diagnosis->orderBy('dcnote_assessment', 'ASC')
        ->groupBy('dcnote_assessment')
        ->pluck('dcnote_assessment')
        ->toArray();

        /*
        $gd_final = [];

        foreach($group_diagnosis as $f) {
            $separate_arr = explode(",", $f);

            $string = $separate_arr[0];

            if(!in_array($string, $gd_final)) {
                $gd_final[] = $separate_arr[0];
            }
        }
        */

        $final_arr = [];

        foreach($group_diagnosis as $g) {
            $pedia_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('facility_id', auth()->user()->itr_facility_id);
            
            $pedia_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('facility_id', auth()->user()->itr_facility_id);
            
            $pedia_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('facility_id', auth()->user()->itr_facility_id);

            $pedia_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('facility_id', auth()->user()->itr_facility_id);

            $adult_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('facility_id', auth()->user()->itr_facility_id);

            $adult_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('facility_id', auth()->user()->itr_facility_id);
            
            $adult_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('facility_id', auth()->user()->itr_facility_id);

            $adult_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('facility_id', auth()->user()->itr_facility_id);

            $senior_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('facility_id', auth()->user()->itr_facility_id);

            $senior_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('facility_id', auth()->user()->itr_facility_id);

            $senior_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('facility_id', auth()->user()->itr_facility_id);

            $senior_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', $g)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('facility_id', auth()->user()->itr_facility_id);

            if(request()->input('type') == 'Daily') {
                $sdate = request()->input('sdate');

                $pedia_old_m = $pedia_old_m->whereDate('consultation_date', $sdate);
                $pedia_new_m = $pedia_new_m->whereDate('consultation_date', $sdate);
                $pedia_old_f = $pedia_old_f->whereDate('consultation_date', $sdate);
                $pedia_new_f = $pedia_new_f->whereDate('consultation_date', $sdate);

                $adult_old_m = $adult_old_m->whereDate('consultation_date', $sdate);
                $adult_new_m = $adult_new_m->whereDate('consultation_date', $sdate);
                $adult_old_f = $adult_old_f->whereDate('consultation_date', $sdate);
                $adult_new_f = $adult_new_f->whereDate('consultation_date', $sdate);

                $senior_old_m = $senior_old_m->whereDate('consultation_date', $sdate);
                $senior_new_m = $senior_new_m->whereDate('consultation_date', $sdate);
                $senior_old_f = $senior_old_f->whereDate('consultation_date', $sdate);
                $senior_new_f = $senior_new_f->whereDate('consultation_date', $sdate);
            }
            else if(request()->input('type') == 'Monthly') {
                $pedia_old_m = $pedia_old_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $pedia_new_m = $pedia_new_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $pedia_old_f = $pedia_old_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $pedia_new_f = $pedia_new_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);

                $adult_old_m = $adult_old_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $adult_new_m = $adult_new_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $adult_old_f = $adult_old_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $adult_new_f = $adult_new_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);

                $senior_old_m = $senior_old_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $senior_new_m = $senior_new_m->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $senior_old_f = $senior_old_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
                $senior_new_f = $senior_new_f->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
            }
            else if(request()->input('type') == 'Yearly') {
                $pedia_old_m = $pedia_old_m->whereYear('consultation_date', $syear);
                $pedia_new_m = $pedia_new_m->whereYear('consultation_date', $syear);
                $pedia_old_f = $pedia_old_f->whereYear('consultation_date', $syear);
                $pedia_new_f = $pedia_new_f->whereYear('consultation_date', $syear);

                $adult_old_m = $adult_old_m->whereYear('consultation_date', $syear);
                $adult_new_m = $adult_new_m->whereYear('consultation_date', $syear);
                $adult_old_f = $adult_old_f->whereYear('consultation_date', $syear);
                $adult_new_f = $adult_new_f->whereYear('consultation_date', $syear);

                $senior_old_m = $senior_old_m->whereYear('consultation_date', $syear);
                $senior_new_m = $senior_new_m->whereYear('consultation_date', $syear);
                $senior_old_f = $senior_old_f->whereYear('consultation_date', $syear);
                $senior_new_f = $senior_new_f->whereYear('consultation_date', $syear);
            }

            $pedia_old_m = $pedia_old_m->count();
            $pedia_new_m = $pedia_new_m->count();
            $pedia_old_f = $pedia_old_f->count();
            $pedia_new_f = $pedia_new_f->count();
            $adult_old_m = $adult_old_m->count();
            $adult_new_m = $adult_new_m->count();
            $adult_old_f = $adult_old_f->count();
            $adult_new_f = $adult_new_f->count();
            $senior_old_m = $senior_old_m->count();
            $senior_new_m = $senior_new_m->count();
            $senior_old_f = $senior_old_f->count();
            $senior_new_f = $senior_new_f->count();

            $gtotal = $pedia_old_m +
            $pedia_new_m +
            $pedia_old_f +
            $pedia_new_f +
            $adult_old_m +
            $adult_new_m +
            $adult_old_f +
            $adult_new_f +
            $senior_old_m +
            $senior_new_m +
            $senior_old_f +
            $senior_new_f;
            
            $final_arr[] = [
                'name' => $g,

                'pedia_old_m' => $pedia_old_m,
                
                'pedia_new_m' => $pedia_new_m,

                'pedia_old_f' => $pedia_old_f,
                
                'pedia_new_f' => $pedia_new_f,

                'adult_old_m' => $adult_old_m,
                
                'adult_new_m' => $adult_new_m,

                'adult_old_f' => $adult_old_f,
                
                'adult_new_f' => $adult_new_f,

                'senior_old_m' => $senior_old_m,
                
                'senior_new_m' => $senior_new_m,

                'senior_old_f' => $senior_old_f,
                
                'senior_new_f' => $senior_new_f,
            ];
        }

        return view('syndromic.cho.opd_summary', [
            'final_arr' => $final_arr,
            'month_flavor' => $month_flavor,

            'smonth' => $smonth,
            'syear' => $syear,
        ]);
    }

    public function ChoFhsisMorbidityReport() {

    }

    public function hospErSummaryReport() {
        
        return view('syndromic.hospital.er_summary');
    }

    public function storeMedicalEvent(Request $r) {
        $r->user()->medicalevent()->create([
            'facility_id' => auth()->user()->itr_facility_id,
            'name' => mb_strtoupper($r->name),
            'description' => ($r->filled('description')) ? mb_strtoupper($r->description) : NULL,
            'oneDayEvent' => $r->oneDayEvent,
            'date_start' => $r->date_start,
            'date_end' => ($r->oneDayEvent == 'Y') ? $r->date_start : $r->date_end,
        ]);

        return redirect()->back()
        ->with('msg', 'Medical Event has been initialized successfully. You may now join if you wish to link records to a Medical Event.')
        ->with('msgtype', 'success');
    }

    public function joinMedicalEvent(Request $r) {
        $d = User::findOrFail(auth()->user()->id);

        $d->itr_medicalevent_id = $r->medical_event_id;

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->back()
        ->with('msg', 'You account has joined the Medical Event. You may now proceed encoding.')
        ->with('msgtype', 'success');
    }

    public function unJoinMedicalEvent(Request $r) {
        $d = User::findOrFail(auth()->user()->id);

        $d->itr_medicalevent_id = NULL;

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->back()
        ->with('msg', 'Your account has left the Medical Event.')
        ->with('msgtype', 'success');
    }

    public function downloadAlphaList() {
        ini_set('max_execution_time', 900);

        $listQuery = SyndromicPatient::where('facility_id', auth()->user()->itr_facility_id);

        function queryGenerator($listQuery) {
            foreach ($listQuery->cursor() as $user) {
                yield $user;
            }
        }

        $sheets = new SheetCollection([
            'ALPHALIST' => queryGenerator($listQuery),
        ]);

        $header_style = (new Style())->setFontBold();
        $rows_style = (new Style())->setShouldWrapText();

        return (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->download('ALPHALIST.xlsx', function ($form) {
            return [
                'PATIENT NUMBER' => $form->unique_opdnumber,
                'SURNAME' => $form->lname,
                'GIVEN NAME' => $form->fname,
                'MIDDLE NAME' => (!is_null($form->mname)) ? $form->mname : 'N/A',
                'BIRTHDATE' => date('m/d/Y', strtotime($form->bdate)),
                'ADDRESS' => $form->getFullAddress(),
            ];
        });
    }

    public function diagSearch() {
        $g = mb_strtoupper(request()->input('diag_name'));

        if(request()->input('diagSearchType') == 'wildcard') {
            $s = SyndromicRecords::where('dcnote_assessment', 'LIKE', '%'.$g.'%');
        }
        else {
            $s = SyndromicRecords::where('dcnote_assessment', $g);
        }

        if(request()->input('sdate') && request()->input('edate')) {
            $date1 = request()->input('sdate');
            $date2 = request()->input('edate');

            if($date1 == $date2) {
                $s = $s->whereDate('consultation_date', $date1);
            }
            else {
                $s = $s->whereBetween('consultation_date', [$date1, $date2]);
            }
        }
        
        $s = $s->where('facility_id', auth()->user()->itr_facility_id)->paginate(10);

        return view('syndromic.search_patient', [
            'list' => $s,
            'search_mode' => 'DIAG',
        ]);
    }

    public function m2BrgyReport() {
        ini_set('max_execution_time', 9999999);

        $brgy = request()->input('brgy');
        $type = request()->input('type');

        if(request()->input('month') && request()->input('year')) {
            $smonth = request()->input('month');
            $syear = request()->input('year');
        }
        else {
            $smonth = date('m');
            $syear = date('Y');
        }

        $date = Carbon::createFromDate($syear, $smonth, 1);
        $month_flavor = $date->format('F').' 1 - '.$date->format('t');

        $group_diagnosis = SyndromicRecords::where('facility_id', auth()->user()->itr_facility_id);
        
        if(request()->input('type') == 'Daily') {
            $sdate = request()->input('sdate');

            $group_diagnosis = $group_diagnosis->whereDate('consultation_date', $sdate);
        }
        else if(request()->input('type') == 'Monthly') {
            $startOfMonth = Carbon::createFromDate($syear, $smonth, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($syear, $smonth, 1)->endOfMonth();

            $group_diagnosis = $group_diagnosis->whereBetween('consultation_date', [$startOfMonth, $endOfMonth]);
        }
        else if(request()->input('type') == 'Yearly') {
            $group_diagnosis = $group_diagnosis::whereYear('consultation_date', $syear);
        }
        else {
            return abort(401);
        }

        if($group_diagnosis->count() <= 0) {
            return 'No Results found.';
        }

        $group_diagnosis = $group_diagnosis->orderBy('dcnote_assessment', 'ASC')
        ->groupBy('dcnote_assessment')
        ->pluck('dcnote_assessment')
        ->toArray();

        $final_arr = [];

        foreach($group_diagnosis as $g) {
            $gtotal_test = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                $q->where('address_brgy_text', $brgy);
            })
            ->where('dcnote_assessment', $g);

            if($type == 'Monthly') {
                $gtotal_test = $gtotal_test->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
            }
            else if($type == 'Yearly') {
                $gtotal_test = $gtotal_test->whereYear('consultation_date', $syear)->count();
            }

            if($gtotal_test != 0) {
                $age1_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [1,4])
                ->where('dcnote_assessment', $g);
    
                $age1_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [1,4])
                ->where('dcnote_assessment', $g);
                
                $age2_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [5,9])
                ->where('dcnote_assessment', $g);
    
                $age2_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [5,9])
                ->where('dcnote_assessment', $g);
    
                $age3_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [10,14])
                ->where('dcnote_assessment', $g);
    
                $age3_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [10,14])
                ->where('dcnote_assessment', $g);
    
                $age4_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [15,19])
                ->where('dcnote_assessment', $g);
    
                $age4_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [15,19])
                ->where('dcnote_assessment', $g);
    
                $age5_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [20,24])
                ->where('dcnote_assessment', $g);
    
                $age5_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [20,24])
                ->where('dcnote_assessment', $g);
    
                $age6_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [25,29])
                ->where('dcnote_assessment', $g);
    
                $age6_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [25,29])
                ->where('dcnote_assessment', $g);
    
                $age7_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [30,34])
                ->where('dcnote_assessment', $g);
    
                $age7_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [30,34])
                ->where('dcnote_assessment', $g);
    
                $age8_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [35,39])
                ->where('dcnote_assessment', $g);
    
                $age8_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [35,39])
                ->where('dcnote_assessment', $g);
    
                $age9_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [40,44])
                ->where('dcnote_assessment', $g);
    
                $age9_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [40,44])
                ->where('dcnote_assessment', $g);
    
                $age10_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [45,49])
                ->where('dcnote_assessment', $g);
    
                $age10_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [45,49])
                ->where('dcnote_assessment', $g);
    
                $age11_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [50,54])
                ->where('dcnote_assessment', $g);
    
                $age11_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [50,54])
                ->where('dcnote_assessment', $g);
    
                $age12_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [55,59])
                ->where('dcnote_assessment', $g);
    
                $age12_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [55,59])
                ->where('dcnote_assessment', $g);
    
                $age13_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [60,64])
                ->where('dcnote_assessment', $g);
    
                $age13_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [60,64])
                ->where('dcnote_assessment', $g);
    
                $age14_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [65,69])
                ->where('dcnote_assessment', $g);
    
                $age14_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->whereBetween('age_years', [65,69])
                ->where('dcnote_assessment', $g);
    
                $age15_male = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->where('age_years', '>=', 70)
                ->where('dcnote_assessment', $g);
    
                $age15_female = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($brgy) {
                    $q->where('gender', 'MALE')
                    ->where('address_brgy_text', $brgy);
                })
                ->where('age_years', '>=', 70)
                ->where('dcnote_assessment', $g);
    
                if($type == 'Monthly') {
                    $age1_male = $age1_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age2_male = $age2_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age3_male = $age3_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age4_male = $age4_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age5_male = $age5_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age6_male = $age6_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age7_male = $age7_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age8_male = $age8_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age9_male = $age9_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age10_male = $age10_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age11_male = $age11_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age12_male = $age12_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age13_male = $age13_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age14_male = $age14_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age15_male = $age15_male->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
    
                    $age1_female = $age1_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age2_female = $age2_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age3_female = $age3_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age4_female = $age4_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age5_female = $age5_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age6_female = $age6_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age7_female = $age7_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age8_female = $age8_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age9_female = $age9_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age10_female = $age10_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age11_female = $age11_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age12_female = $age12_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age13_female = $age13_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age14_female = $age14_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                    $age15_female = $age15_female->whereBetween('consultation_date', [$startOfMonth, $endOfMonth])->count();
                }
                else if($type == 'Yearly') {
                    $age1_male = $age1_male->whereYear('consultation_date', $syear)->count();
                    $age2_male = $age2_male->whereYear('consultation_date', $syear)->count();
                    $age3_male = $age3_male->whereYear('consultation_date', $syear)->count();
                    $age4_male = $age4_male->whereYear('consultation_date', $syear)->count();
                    $age5_male = $age5_male->whereYear('consultation_date', $syear)->count();
                    $age6_male = $age6_male->whereYear('consultation_date', $syear)->count();
                    $age7_male = $age7_male->whereYear('consultation_date', $syear)->count();
                    $age8_male = $age8_male->whereYear('consultation_date', $syear)->count();
                    $age9_male = $age9_male->whereYear('consultation_date', $syear)->count();
                    $age10_male = $age10_male->whereYear('consultation_date', $syear)->count();
                    $age11_male = $age11_male->whereYear('consultation_date', $syear)->count();
                    $age12_male = $age12_male->whereYear('consultation_date', $syear)->count();
                    $age13_male = $age13_male->whereYear('consultation_date', $syear)->count();
                    $age14_male = $age14_male->whereYear('consultation_date', $syear)->count();
                    $age15_male = $age15_male->whereYear('consultation_date', $syear)->count();
    
                    $age1_female = $age1_female->whereYear('consultation_date', $syear)->count();
                    $age2_female = $age2_female->whereYear('consultation_date', $syear)->count();
                    $age3_female = $age3_female->whereYear('consultation_date', $syear)->count();
                    $age4_female = $age4_female->whereYear('consultation_date', $syear)->count();
                    $age5_female = $age5_female->whereYear('consultation_date', $syear)->count();
                    $age6_female = $age6_female->whereYear('consultation_date', $syear)->count();
                    $age7_female = $age7_female->whereYear('consultation_date', $syear)->count();
                    $age8_female = $age8_female->whereYear('consultation_date', $syear)->count();
                    $age9_female = $age9_female->whereYear('consultation_date', $syear)->count();
                    $age10_female = $age10_female->whereYear('consultation_date', $syear)->count();
                    $age11_female = $age11_female->whereYear('consultation_date', $syear)->count();
                    $age12_female = $age12_female->whereYear('consultation_date', $syear)->count();
                    $age13_female = $age13_female->whereYear('consultation_date', $syear)->count();
                    $age14_female = $age14_female->whereYear('consultation_date', $syear)->count();
                    $age15_female = $age15_female->whereYear('consultation_date', $syear)->count();
                }
    
                $agetotal_male = $age1_male + $age2_male + $age3_male + $age4_male + $age5_male + $age6_male + $age7_male + $age8_male + $age9_male + $age10_male + $age11_male + $age12_male + $age13_male + $age14_male + $age15_male;
                $agetotal_female = $age1_female + $age2_female + $age3_female + $age4_female + $age5_female + $age6_female + $age7_female + $age8_female + $age9_female + $age10_female + $age11_female + $age12_female + $age13_female + $age14_female + $age15_female;
    
                if(($agetotal_male + $agetotal_female) != 0) {
                    $final_arr[] = [
                        'disease' => $g,
                        'age1_male' => $age1_male,
                        'age2_male' => $age2_male,
                        'age3_male' => $age3_male,
                        'age4_male' => $age4_male,
                        'age5_male' => $age5_male,
                        'age6_male' => $age6_male,
                        'age7_male' => $age7_male,
                        'age8_male' => $age8_male,
                        'age9_male' => $age9_male,
                        'age10_male' => $age10_male,
                        'age11_male' => $age11_male,
                        'age12_male' => $age12_male,
                        'age13_male' => $age13_male,
                        'age14_male' => $age14_male,
                        'age15_male' => $age15_male,
                        'agetotal_male' => $agetotal_male,
        
                        'age1_female' => $age1_female,
                        'age2_female' => $age2_female,
                        'age3_female' => $age3_female,
                        'age4_female' => $age4_female,
                        'age5_female' => $age5_female,
                        'age6_female' => $age6_female,
                        'age7_female' => $age7_female,
                        'age8_female' => $age8_female,
                        'age9_female' => $age9_female,
                        'age10_female' => $age10_female,
                        'age11_female' => $age11_female,
                        'age12_female' => $age12_female,
                        'age13_female' => $age13_female,
                        'age14_female' => $age14_female,
                        'age15_female' => $age15_female,
                        'agetotal_female' => $agetotal_female,
                    ];
                }
            }
        }

        $tb_array = [
            'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
            'A16.1 Tuberculosis of lung, bacteriological and histological examination not done',
            'A16.0 Tuberculosis of lung, bacteriologically and histologically negative',
            'A18 Tuberculosis of other organs',
        ];

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        foreach($tb_array as $tb) {
            $age1_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [1,4])
            ->where('sex', 'M');

            $age1_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [1,4])
            ->where('sex', 'F');
            
            $age2_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [5,9])
            ->where('sex', 'M');

            $age2_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [5,9])
            ->where('sex', 'F');

            $age3_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [10,14])
            ->where('sex', 'M');

            $age3_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [10,14])
            ->where('sex', 'F');

            $age4_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [15,19])
            ->where('sex', 'M');

            $age4_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [15,19])
            ->where('sex', 'F');

            $age5_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [20,24])
            ->where('sex', 'M');

            $age5_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [20,24])
            ->where('sex', 'F');

            $age6_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [25,29])
            ->where('sex', 'M');

            $age6_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [25,29])
            ->where('sex', 'F');

            $age7_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [30,34])
            ->where('sex', 'M');

            $age7_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [30,34])
            ->where('sex', 'F');

            $age8_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [35,39])
            ->where('sex', 'M');

            $age8_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [35,39])
            ->where('sex', 'F');

            $age9_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [40,44])
            ->where('sex', 'M');

            $age9_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [40,44])
            ->where('sex', 'F');

            $age10_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [45,49])
            ->where('sex', 'M');

            $age10_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [45,49])
            ->where('sex', 'F');

            $age11_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [50,54])
            ->where('sex', 'M');

            $age11_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [50,54])
            ->where('sex', 'F');

            $age12_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [55,59])
            ->where('sex', 'M');

            $age12_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [55,59])
            ->where('sex', 'F');

            $age13_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [60,64])
            ->where('sex', 'M');

            $age13_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [60,64])
            ->where('sex', 'F');

            $age14_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [65,69])
            ->where('sex', 'M');

            $age14_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->whereBetween('age', [65,69])
            ->where('sex', 'F');

            $age15_male = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->where('age', '>=', 70)
            ->where('sex', 'M');

            $age15_female = FhsisTbdotsMorbidity::where('brgy', $brgy)
            ->where('age', '>=', 70)
            ->where('sex', 'F');

            if($type == 'Monthly') {
                $age1_male = $age1_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age2_male = $age2_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age3_male = $age3_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age4_male = $age4_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age5_male = $age5_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age6_male = $age6_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age7_male = $age7_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age8_male = $age8_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age9_male = $age9_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age10_male = $age10_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age11_male = $age11_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age12_male = $age12_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age13_male = $age13_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age14_male = $age14_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age15_male = $age15_male->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);

                $age1_female = $age1_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age2_female = $age2_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age3_female = $age3_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age4_female = $age4_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age5_female = $age5_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age6_female = $age6_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age7_female = $age7_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age8_female = $age8_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age9_female = $age9_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age10_female = $age10_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age11_female = $age11_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age12_female = $age12_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age13_female = $age13_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age14_female = $age14_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
                $age15_female = $age15_female->whereBetween('date_started_tx', [$startOfMonth, $endOfMonth]);
            }
            else if($type == 'Yearly') {
                $age1_male = $age1_male->whereYear('date_started_tx', $syear);
                $age2_male = $age2_male->whereYear('date_started_tx', $syear);
                $age3_male = $age3_male->whereYear('date_started_tx', $syear);
                $age4_male = $age4_male->whereYear('date_started_tx', $syear);
                $age5_male = $age5_male->whereYear('date_started_tx', $syear);
                $age6_male = $age6_male->whereYear('date_started_tx', $syear);
                $age7_male = $age7_male->whereYear('date_started_tx', $syear);
                $age8_male = $age8_male->whereYear('date_started_tx', $syear);
                $age9_male = $age9_male->whereYear('date_started_tx', $syear);
                $age10_male = $age10_male->whereYear('date_started_tx', $syear);
                $age11_male = $age11_male->whereYear('date_started_tx', $syear);
                $age12_male = $age12_male->whereYear('date_started_tx', $syear);
                $age13_male = $age13_male->whereYear('date_started_tx', $syear);
                $age14_male = $age14_male->whereYear('date_started_tx', $syear);
                $age15_male = $age15_male->whereYear('date_started_tx', $syear);

                $age1_female = $age1_female->whereYear('date_started_tx', $syear);
                $age2_female = $age2_female->whereYear('date_started_tx', $syear);
                $age3_female = $age3_female->whereYear('date_started_tx', $syear);
                $age4_female = $age4_female->whereYear('date_started_tx', $syear);
                $age5_female = $age5_female->whereYear('date_started_tx', $syear);
                $age6_female = $age6_female->whereYear('date_started_tx', $syear);
                $age7_female = $age7_female->whereYear('date_started_tx', $syear);
                $age8_female = $age8_female->whereYear('date_started_tx', $syear);
                $age9_female = $age9_female->whereYear('date_started_tx', $syear);
                $age10_female = $age10_female->whereYear('date_started_tx', $syear);
                $age11_female = $age11_female->whereYear('date_started_tx', $syear);
                $age12_female = $age12_female->whereYear('date_started_tx', $syear);
                $age13_female = $age13_female->whereYear('date_started_tx', $syear);
                $age14_female = $age14_female->whereYear('date_started_tx', $syear);
                $age15_female = $age15_female->whereYear('date_started_tx', $syear);
            }

            if($tb == 'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture') {
                $age1_male = $age1_male->where('xpert_result', 'MTB Detected')->count();
                $age2_male = $age2_male->where('xpert_result', 'MTB Detected')->count();
                $age3_male = $age3_male->where('xpert_result', 'MTB Detected')->count();
                $age4_male = $age4_male->where('xpert_result', 'MTB Detected')->count();
                $age5_male = $age5_male->where('xpert_result', 'MTB Detected')->count();
                $age6_male = $age6_male->where('xpert_result', 'MTB Detected')->count();
                $age7_male = $age7_male->where('xpert_result', 'MTB Detected')->count();
                $age8_male = $age8_male->where('xpert_result', 'MTB Detected')->count();
                $age9_male = $age9_male->where('xpert_result', 'MTB Detected')->count();
                $age10_male = $age10_male->where('xpert_result', 'MTB Detected')->count();
                $age11_male = $age11_male->where('xpert_result', 'MTB Detected')->count();
                $age12_male = $age12_male->where('xpert_result', 'MTB Detected')->count();
                $age13_male = $age13_male->where('xpert_result', 'MTB Detected')->count();
                $age14_male = $age14_male->where('xpert_result', 'MTB Detected')->count();
                $age15_male = $age15_male->where('xpert_result', 'MTB Detected')->count();

                $age1_female = $age1_female->where('xpert_result', 'MTB Detected')->count();
                $age2_female = $age2_female->where('xpert_result', 'MTB Detected')->count();
                $age3_female = $age3_female->where('xpert_result', 'MTB Detected')->count();
                $age4_female = $age4_female->where('xpert_result', 'MTB Detected')->count();
                $age5_female = $age5_female->where('xpert_result', 'MTB Detected')->count();
                $age6_female = $age6_female->where('xpert_result', 'MTB Detected')->count();
                $age7_female = $age7_female->where('xpert_result', 'MTB Detected')->count();
                $age8_female = $age8_female->where('xpert_result', 'MTB Detected')->count();
                $age9_female = $age9_female->where('xpert_result', 'MTB Detected')->count();
                $age10_female = $age10_female->where('xpert_result', 'MTB Detected')->count();
                $age11_female = $age11_female->where('xpert_result', 'MTB Detected')->count();
                $age12_female = $age12_female->where('xpert_result', 'MTB Detected')->count();
                $age13_female = $age13_female->where('xpert_result', 'MTB Detected')->count();
                $age14_female = $age14_female->where('xpert_result', 'MTB Detected')->count();
                $age15_female = $age15_female->where('xpert_result', 'MTB Detected')->count();
            }
            else if($tb == 'A16.1 Tuberculosis of lung, bacteriological and histological examination not done') {
                $age1_male = $age1_male->where('xpert_result', 'Not Done')->count();
                $age2_male = $age2_male->where('xpert_result', 'Not Done')->count();
                $age3_male = $age3_male->where('xpert_result', 'Not Done')->count();
                $age4_male = $age4_male->where('xpert_result', 'Not Done')->count();
                $age5_male = $age5_male->where('xpert_result', 'Not Done')->count();
                $age6_male = $age6_male->where('xpert_result', 'Not Done')->count();
                $age7_male = $age7_male->where('xpert_result', 'Not Done')->count();
                $age8_male = $age8_male->where('xpert_result', 'Not Done')->count();
                $age9_male = $age9_male->where('xpert_result', 'Not Done')->count();
                $age10_male = $age10_male->where('xpert_result', 'Not Done')->count();
                $age11_male = $age11_male->where('xpert_result', 'Not Done')->count();
                $age12_male = $age12_male->where('xpert_result', 'Not Done')->count();
                $age13_male = $age13_male->where('xpert_result', 'Not Done')->count();
                $age14_male = $age14_male->where('xpert_result', 'Not Done')->count();
                $age15_male = $age15_male->where('xpert_result', 'Not Done')->count();

                $age1_female = $age1_female->where('xpert_result', 'Not Done')->count();
                $age2_female = $age2_female->where('xpert_result', 'Not Done')->count();
                $age3_female = $age3_female->where('xpert_result', 'Not Done')->count();
                $age4_female = $age4_female->where('xpert_result', 'Not Done')->count();
                $age5_female = $age5_female->where('xpert_result', 'Not Done')->count();
                $age6_female = $age6_female->where('xpert_result', 'Not Done')->count();
                $age7_female = $age7_female->where('xpert_result', 'Not Done')->count();
                $age8_female = $age8_female->where('xpert_result', 'Not Done')->count();
                $age9_female = $age9_female->where('xpert_result', 'Not Done')->count();
                $age10_female = $age10_female->where('xpert_result', 'Not Done')->count();
                $age11_female = $age11_female->where('xpert_result', 'Not Done')->count();
                $age12_female = $age12_female->where('xpert_result', 'Not Done')->count();
                $age13_female = $age13_female->where('xpert_result', 'Not Done')->count();
                $age14_female = $age14_female->where('xpert_result', 'Not Done')->count();
                $age15_female = $age15_female->where('xpert_result', 'Not Done')->count();
            }
            else if($tb == 'A16.0 Tuberculosis of lung, bacteriologically and histologically negative') {
                $age1_male = $age1_male->where('xpert_result', 'MTB Not Detected')->count();
                $age2_male = $age2_male->where('xpert_result', 'MTB Not Detected')->count();
                $age3_male = $age3_male->where('xpert_result', 'MTB Not Detected')->count();
                $age4_male = $age4_male->where('xpert_result', 'MTB Not Detected')->count();
                $age5_male = $age5_male->where('xpert_result', 'MTB Not Detected')->count();
                $age6_male = $age6_male->where('xpert_result', 'MTB Not Detected')->count();
                $age7_male = $age7_male->where('xpert_result', 'MTB Not Detected')->count();
                $age8_male = $age8_male->where('xpert_result', 'MTB Not Detected')->count();
                $age9_male = $age9_male->where('xpert_result', 'MTB Not Detected')->count();
                $age10_male = $age10_male->where('xpert_result', 'MTB Not Detected')->count();
                $age11_male = $age11_male->where('xpert_result', 'MTB Not Detected')->count();
                $age12_male = $age12_male->where('xpert_result', 'MTB Not Detected')->count();
                $age13_male = $age13_male->where('xpert_result', 'MTB Not Detected')->count();
                $age14_male = $age14_male->where('xpert_result', 'MTB Not Detected')->count();
                $age15_male = $age15_male->where('xpert_result', 'MTB Not Detected')->count();

                $age1_female = $age1_female->where('xpert_result', 'MTB Not Detected')->count();
                $age2_female = $age2_female->where('xpert_result', 'MTB Not Detected')->count();
                $age3_female = $age3_female->where('xpert_result', 'MTB Not Detected')->count();
                $age4_female = $age4_female->where('xpert_result', 'MTB Not Detected')->count();
                $age5_female = $age5_female->where('xpert_result', 'MTB Not Detected')->count();
                $age6_female = $age6_female->where('xpert_result', 'MTB Not Detected')->count();
                $age7_female = $age7_female->where('xpert_result', 'MTB Not Detected')->count();
                $age8_female = $age8_female->where('xpert_result', 'MTB Not Detected')->count();
                $age9_female = $age9_female->where('xpert_result', 'MTB Not Detected')->count();
                $age10_female = $age10_female->where('xpert_result', 'MTB Not Detected')->count();
                $age11_female = $age11_female->where('xpert_result', 'MTB Not Detected')->count();
                $age12_female = $age12_female->where('xpert_result', 'MTB Not Detected')->count();
                $age13_female = $age13_female->where('xpert_result', 'MTB Not Detected')->count();
                $age14_female = $age14_female->where('xpert_result', 'MTB Not Detected')->count();
                $age15_female = $age15_female->where('xpert_result', 'MTB Not Detected')->count();
            }
            else if($tb == 'A18 Tuberculosis of other organs') {
                $age1_male = $age1_male->where('ana_site', 'EP')->count();
                $age2_male = $age2_male->where('ana_site', 'EP')->count();
                $age3_male = $age3_male->where('ana_site', 'EP')->count();
                $age4_male = $age4_male->where('ana_site', 'EP')->count();
                $age5_male = $age5_male->where('ana_site', 'EP')->count();
                $age6_male = $age6_male->where('ana_site', 'EP')->count();
                $age7_male = $age7_male->where('ana_site', 'EP')->count();
                $age8_male = $age8_male->where('ana_site', 'EP')->count();
                $age9_male = $age9_male->where('ana_site', 'EP')->count();
                $age10_male = $age10_male->where('ana_site', 'EP')->count();
                $age11_male = $age11_male->where('ana_site', 'EP')->count();
                $age12_male = $age12_male->where('ana_site', 'EP')->count();
                $age13_male = $age13_male->where('ana_site', 'EP')->count();
                $age14_male = $age14_male->where('ana_site', 'EP')->count();
                $age15_male = $age15_male->where('ana_site', 'EP')->count();

                $age1_female = $age1_female->where('ana_site', 'EP')->count();
                $age2_female = $age2_female->where('ana_site', 'EP')->count();
                $age3_female = $age3_female->where('ana_site', 'EP')->count();
                $age4_female = $age4_female->where('ana_site', 'EP')->count();
                $age5_female = $age5_female->where('ana_site', 'EP')->count();
                $age6_female = $age6_female->where('ana_site', 'EP')->count();
                $age7_female = $age7_female->where('ana_site', 'EP')->count();
                $age8_female = $age8_female->where('ana_site', 'EP')->count();
                $age9_female = $age9_female->where('ana_site', 'EP')->count();
                $age10_female = $age10_female->where('ana_site', 'EP')->count();
                $age11_female = $age11_female->where('ana_site', 'EP')->count();
                $age12_female = $age12_female->where('ana_site', 'EP')->count();
                $age13_female = $age13_female->where('ana_site', 'EP')->count();
                $age14_female = $age14_female->where('ana_site', 'EP')->count();
                $age15_female = $age15_female->where('ana_site', 'EP')->count();
            }

            $agetotal_male = $age1_male + $age2_male + $age3_male + $age4_male + $age5_male + $age6_male + $age7_male + $age8_male + $age9_male + $age10_male + $age11_male + $age12_male + $age13_male + $age14_male + $age15_male;
            $agetotal_female = $age1_female + $age2_female + $age3_female + $age4_female + $age5_female + $age6_female + $age7_female + $age8_female + $age9_female + $age10_female + $age11_female + $age12_female + $age13_female + $age14_female + $age15_female;

            if(($agetotal_male + $agetotal_female) != 0) {
                $final_arr[] = [
                    'disease' => $tb,
                    'age1_male' => $age1_male,
                    'age2_male' => $age2_male,
                    'age3_male' => $age3_male,
                    'age4_male' => $age4_male,
                    'age5_male' => $age5_male,
                    'age6_male' => $age6_male,
                    'age7_male' => $age7_male,
                    'age8_male' => $age8_male,
                    'age9_male' => $age9_male,
                    'age10_male' => $age10_male,
                    'age11_male' => $age11_male,
                    'age12_male' => $age12_male,
                    'age13_male' => $age13_male,
                    'age14_male' => $age14_male,
                    'age15_male' => $age15_male,
                    'agetotal_male' => $agetotal_male,
    
                    'age1_female' => $age1_female,
                    'age2_female' => $age2_female,
                    'age3_female' => $age3_female,
                    'age4_female' => $age4_female,
                    'age5_female' => $age5_female,
                    'age6_female' => $age6_female,
                    'age7_female' => $age7_female,
                    'age8_female' => $age8_female,
                    'age9_female' => $age9_female,
                    'age10_female' => $age10_female,
                    'age11_female' => $age11_female,
                    'age12_female' => $age12_female,
                    'age13_female' => $age13_female,
                    'age14_female' => $age14_female,
                    'age15_female' => $age15_female,
                    'agetotal_female' => $agetotal_female,
                ];
            }
        }

        return view('efhsis.tbdots.dashboard', [
            'final_array' => $final_arr,
            'brgy_list' => $brgy_list,
        ]);
    }

    public function hospSummaryReportV2() {
        //Check if Existing Job 10 minutes ago was created by User
        $queue_check = ExportJobs::where('created_by', auth()->user()->id)
        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-5 Minutes')))
        ->first();

        if(1 == 1) {
            $year = request()->input('year');
            $month = request()->input('month');
            $type = request()->input('type');

            $start = Carbon::createFromDate($year, $month, 01)->startOfMonth();

            if($type == 'OPD') {
                $pre_title = 'OPD Summary Report for ';
            }
            else if($type == 'ER') {
                $pre_title = 'ER Summary Report for ';
            }   
            else {
                return abort(401);
            }

            //Call Export Job
            $c = ExportJobs::create([
                'name' => $pre_title.$start->format('M Y'),
                'for_module' => 'OPD',
                'status' => 'pending',
                //'date_finished'
                //'filename',
                'created_by' => auth()->user()->id,
                'facility_id' => auth()->user()->itr_facility_id,
            ]);

            CallOpdErExport::dispatch(auth()->user()->id, $c->id, $year, $month, $type);

            return redirect()->route('export_index')
            ->with('msg', 'Your download request is now being requested. The server will now prepare the file. Please refresh this page after 5-10 minutes or more until the status turns to completed.')
            ->with('msgtype', 'success');
        }
        
    }
}
