<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ExportJobs;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\SyndromicRecords;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Queue\InteractsWithQueue;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CallOpdErExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $user_id;
    protected $task_id;
    protected $year;
    protected $month;
    protected $type;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $task_id, $year, $month, $type)
    {
        $this->user_id = $user_id;
        $this->task_id = $task_id;
        $this->year = $year;
        $this->month = $month;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $year = $this->year;
        $month = $this->month;
        $type = $this->type;

        $u = User::findOrFail($this->user_id);

        $date = Carbon::createFromDate($year, $month, 1);
        $month_flavor = $date->format('F').' 1 - '.$date->format('t');

        $start = Carbon::createFromDate($year, $month, 01)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 01)->endOfMonth();
        
        $spreadsheet = IOFactory::load(storage_path('OPDSUMMARYREPORT_TEMPLATE.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', $type);
        $sheet->setCellValue('A2', $u->opdfacility->facility_name);
        $sheet->setCellValue('A3', 'Date: '.$start->format('M, Y'));

        $group_diagnosis = SyndromicRecords::where('hosp_identifier', $type)
        ->where('facility_id', $u->itr_facility_id)
        ->where('hospital_completion', 'PART2')
        ->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

        $group_diagnosis = $group_diagnosis->orderBy('dcnote_assessment', 'DESC')
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

        $cRow = 4;
        $secondTotal_cRow = 7;

        foreach($gd_final as $g) {
            // Append row after row 2
            $sheet->insertNewRowBefore(4, 1); // Insert one new row before row 2
            $secondTotal_cRow++;

            $pedia_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $pedia_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $pedia_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $pedia_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '<=', 19)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');
            
            $adult_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $adult_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->whereBetween('age_years', [20,59])
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'FOLLOW-UP VISIT')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2');

            $senior_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('dcnote_assessment', 'LIKE', $g.'%')
            ->where('disposition', 'SENT TO JAIL')
            ->where('age_years', '>=', 60)
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
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
                $pedia_old_m = $pedia_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_new_m = $pedia_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_police_m = $pedia_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_old_f = $pedia_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_new_f = $pedia_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_police_f = $pedia_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

                $adult_old_m = $adult_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_new_m = $adult_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_police_m = $adult_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_old_f = $adult_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_new_f = $adult_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_police_f = $adult_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

                $senior_old_m = $senior_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_new_m = $senior_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_police_m = $senior_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_old_f = $senior_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_new_f = $senior_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_police_f = $senior_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
            }
            
            /*
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
            */

            $rowb = $pedia_old_m->count();
            $rowc = $pedia_new_m->count();
            $rowd = $pedia_police_m->count();
            $rowe = $pedia_old_f->count();
            $rowf = $pedia_new_f->count();
            $rowg = $pedia_police_f->count();
            $rowh = $adult_old_m->count();
            $rowi = $adult_new_m->count();
            $rowj = $adult_police_m->count();
            $rowk = $adult_old_f->count();
            $rowl = $adult_new_f->count();
            $rowm = $adult_police_f->count();
            $rown = $senior_old_m->count();
            $rowo = $senior_new_m->count();
            $rowp = $senior_police_m->count();
            $rowq = $senior_old_f->count();
            $rowr = $senior_new_f->count();
            $rows = $senior_police_f->count();
            
            $rowt = $rowb + $rowc + $rowd + $rowe + $rowf + $rowg + $rowh + $rowi + $rowj + $rowk + $rowl + $rowm + $rown + $rowo + $rowp + $rowq + $rowr + $rows;

            $sheet->setCellValue('A4', $g);
            $sheet->setCellValue('B4', $rowb);
            $sheet->setCellValue('C4', $rowc);
            $sheet->setCellValue('D4', $rowd);
            $sheet->setCellValue('E4', $rowe);
            $sheet->setCellValue('F4', $rowf);
            $sheet->setCellValue('G4', $rowg);
            $sheet->setCellValue('H4', $rowh);
            $sheet->setCellValue('I4', $rowi);
            $sheet->setCellValue('J4', $rowj);
            $sheet->setCellValue('K4', $rowk);
            $sheet->setCellValue('L4', $rowl);
            $sheet->setCellValue('M4', $rowm);
            $sheet->setCellValue('N4', $rown);
            $sheet->setCellValue('O4', $rowo);
            $sheet->setCellValue('P4', $rowp);
            $sheet->setCellValue('Q4', $rowq);
            $sheet->setCellValue('R4', $rowr);
            $sheet->setCellValue('S4', $rows);
            $sheet->setCellValue('T4', $rowt);
            $cRow++;
        }

        $sheet->setCellValue('B'.$cRow, '=SUM(B4:B'.($cRow -1).')');
        $sheet->setCellValue('C'.$cRow, '=SUM(C4:C'.($cRow -1).')');
        $sheet->setCellValue('D'.$cRow, '=SUM(D4:D'.($cRow -1).')');
        $sheet->setCellValue('E'.$cRow, '=SUM(E4:E'.($cRow -1).')');
        $sheet->setCellValue('F'.$cRow, '=SUM(F4:F'.($cRow -1).')');
        $sheet->setCellValue('G'.$cRow, '=SUM(G4:G'.($cRow -1).')');
        $sheet->setCellValue('H'.$cRow, '=SUM(H4:H'.($cRow -1).')');
        $sheet->setCellValue('I'.$cRow, '=SUM(I4:I'.($cRow -1).')');
        $sheet->setCellValue('J'.$cRow, '=SUM(J4:J'.($cRow -1).')');
        $sheet->setCellValue('K'.$cRow, '=SUM(K4:K'.($cRow -1).')');
        $sheet->setCellValue('L'.$cRow, '=SUM(L4:L'.($cRow -1).')');
        $sheet->setCellValue('M'.$cRow, '=SUM(M4:M'.($cRow -1).')');
        $sheet->setCellValue('N'.$cRow, '=SUM(N4:N'.($cRow -1).')');
        $sheet->setCellValue('O'.$cRow, '=SUM(O4:O'.($cRow -1).')');
        $sheet->setCellValue('P'.$cRow, '=SUM(P4:P'.($cRow -1).')');
        $sheet->setCellValue('Q'.$cRow, '=SUM(Q4:Q'.($cRow -1).')');
        $sheet->setCellValue('R'.$cRow, '=SUM(R4:R'.($cRow -1).')');
        $sheet->setCellValue('S'.$cRow, '=SUM(S4:S'.($cRow -1).')');
        //$sheet->setCellValue('T4', '=SUM(T4:B'.($cRow -1).')');

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

        $opd_master_array = array_reverse($opd_master_array);

        $second_array = [];

        foreach($opd_master_array as $o) {
            $sheet->insertNewRowBefore($cRow+3, 1); // Insert one new row before row 2
            $secondTotal_cRow++;
            
            $pedia_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $pedia_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $pedia_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $pedia_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $pedia_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $pedia_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '<=', 19)
            ->where('disposition', 'SENT TO JAIL');

            //ADULT

            $adult_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $adult_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $adult_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $adult_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $adult_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $adult_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->whereBetween('age_years', [20,59])
            ->where('disposition', 'SENT TO JAIL');

            //SENIOR

            $senior_old_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $senior_new_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $senior_police_m = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'MALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('tags', $o)
            ->where('disposition', 'SENT TO JAIL');

            $senior_old_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'FOLLOW-UP VISIT');

            $senior_new_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
            ->where('hospital_completion', 'PART2')
            ->where('age_years', '>=', 60)
            ->where('nature_of_visit', 'NEW CONSULTATION/CASE');

            $senior_police_f = SyndromicRecords::whereHas('syndromic_patient', function($q) {
                $q->where('gender', 'FEMALE');
            })
            ->where('hosp_identifier', $type)
            ->where('facility_id', $u->itr_facility_id)
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
                $pedia_old_m = $pedia_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_new_m = $pedia_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_police_m = $pedia_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_old_f = $pedia_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_new_f = $pedia_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $pedia_police_f = $pedia_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

                $adult_old_m = $adult_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_new_m = $adult_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_police_m = $adult_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_old_f = $adult_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_new_f = $adult_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $adult_police_f = $adult_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

                $senior_old_m = $senior_old_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_new_m = $senior_new_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_police_m = $senior_police_m->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_old_f = $senior_old_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_new_f = $senior_new_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
                $senior_police_f = $senior_police_f->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
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

            /*
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
            */

            $rowb = $pedia_old_m;
            $rowc = $pedia_new_m;
            $rowd = $pedia_police_m;
            $rowe = $pedia_old_f;
            $rowf = $pedia_new_f;
            $rowg = $pedia_police_f;
            $rowh = $adult_old_m;
            $rowi = $adult_new_m;
            $rowj = $adult_police_m;
            $rowk = $adult_old_f;
            $rowl = $adult_new_f;
            $rowm = $adult_police_f;
            $rown = $senior_old_m;
            $rowo = $senior_new_m;
            $rowp = $senior_police_m;
            $rowq = $senior_old_f;
            $rowr = $senior_new_f;
            $rows = $senior_police_f;
            
            $rowt = $rowb + $rowc + $rowd + $rowe + $rowf + $rowg + $rowh + $rowi + $rowj + $rowk + $rowl + $rowm + $rown + $rowo + $rowp + $rowq + $rowr + $rows;

            $sheet->setCellValue('A'.$cRow+3, $o);
            $sheet->setCellValue('B'.$cRow+3, $rowb);
            $sheet->setCellValue('C'.$cRow+3, $rowc);
            $sheet->setCellValue('D'.$cRow+3, $rowd);
            $sheet->setCellValue('E'.$cRow+3, $rowe);
            $sheet->setCellValue('F'.$cRow+3, $rowf);
            $sheet->setCellValue('G'.$cRow+3, $rowg);
            $sheet->setCellValue('H'.$cRow+3, $rowh);
            $sheet->setCellValue('I'.$cRow+3, $rowi);
            $sheet->setCellValue('J'.$cRow+3, $rowj);
            $sheet->setCellValue('K'.$cRow+3, $rowk);
            $sheet->setCellValue('L'.$cRow+3, $rowl);
            $sheet->setCellValue('M'.$cRow+3, $rowm);
            $sheet->setCellValue('N'.$cRow+3, $rown);
            $sheet->setCellValue('O'.$cRow+3, $rowo);
            $sheet->setCellValue('P'.$cRow+3, $rowp);
            $sheet->setCellValue('Q'.$cRow+3, $rowq);
            $sheet->setCellValue('R'.$cRow+3, $rowr);
            $sheet->setCellValue('S'.$cRow+3, $rows);
            $sheet->setCellValue('T'.$cRow+3, $rowt);
            //$cRow++;
        }

        $sheet->setCellValue('B'.$secondTotal_cRow, '=SUM(B'.($cRow+3).':B'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('C'.$secondTotal_cRow, '=SUM(C'.($cRow+3).':C'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('D'.$secondTotal_cRow, '=SUM(D'.($cRow+3).':D'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('E'.$secondTotal_cRow, '=SUM(E'.($cRow+3).':E'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('F'.$secondTotal_cRow, '=SUM(F'.($cRow+3).':F'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('G'.$secondTotal_cRow, '=SUM(G'.($cRow+3).':G'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('H'.$secondTotal_cRow, '=SUM(H'.($cRow+3).':H'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('I'.$secondTotal_cRow, '=SUM(I'.($cRow+3).':I'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('J'.$secondTotal_cRow, '=SUM(J'.($cRow+3).':J'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('K'.$secondTotal_cRow, '=SUM(K'.($cRow+3).':K'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('L'.$secondTotal_cRow, '=SUM(L'.($cRow+3).':L'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('M'.$secondTotal_cRow, '=SUM(M'.($cRow+3).':M'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('N'.$secondTotal_cRow, '=SUM(N'.($cRow+3).':N'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('O'.$secondTotal_cRow, '=SUM(O'.($cRow+3).':O'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('P'.$secondTotal_cRow, '=SUM(P'.($cRow+3).':P'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('Q'.$secondTotal_cRow, '=SUM(Q'.($cRow+3).':Q'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('R'.$secondTotal_cRow, '=SUM(R'.($cRow+3).':R'.($secondTotal_cRow -1).')');
        $sheet->setCellValue('S'.$secondTotal_cRow, '=SUM(S'.($cRow+3).':S'.($secondTotal_cRow -1).')');
        //$sheet->setCellValue('T'.$secondTotal_cRow, '=SUM(T'.($cRow+3).':T'.($secondTotal_cRow -1).')');

        //$sheet->setCellValue('A4', 'BILAT');

        $filename = 'OPD_Summary_'.$start->format('M_Y').'_'.Str::random(5).'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('export_jobs/').$filename);

        $job_update = ExportJobs::where('id', $this->task_id)->update([
            'status' => 'completed',
            'filename' => $filename,
            'date_finished' => date('Y-m-d H:i:s'),
        ]);
    }
}
