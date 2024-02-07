<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use Illuminate\Http\Request;
use App\Models\PregnancyTrackingForm;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelFactory;

class PregnancyTrackingController extends Controller
{
    public function index() {
        return view('pregnancytracking.index');
    }

    public function new() {
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();
        
        return view('pregnancytracking.new', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function store(Request $r) {
        if(!(PregnancyTrackingForm::ifDuplicateFound($r->lname, $r->fname, $r->mname))) {
            $create = $r->user()->pregnancytrackingform()->create([
                'catchment_brgy' => $r->catchment_brgy,
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,

                'age' => $r->age,
                'street_purok' => mb_strtoupper($r->street_purok),
                'lmp' => $r->lmp,
                'edc' => $r->edc,

                'pc_done1_check' => ($r->pc_done1_check) ? 'Y' : 'N',
                'pc_done2_check' => ($r->pc_done2_check) ? 'Y' : 'N',
                'pc_done3_check' => ($r->pc_done3_check) ? 'Y' : 'N',
                'pc_done4_check' => ($r->pc_done4_check) ? 'Y' : 'N',
                
                'pc_done1' => $r->pc_done1,
                'pc_done2' => $r->pc_done2,
                'pc_done3' => $r->pc_done3,
                'pc_done4' => $r->pc_done4,
                'outcome'  => $r->outcome,
                'accomplished_by' => $r->accomplished_by,
            ]);

            return redirect()->route('ptracking_index')
            ->with('msg', 'Patient '.$create->getNameFormal().' was successfully created.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Patient already exists in the Pregnancy Tracking Form Database. Please double check and try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function edit() {

    }

    public function update() {

    }

    public function monthlyreport1(Request $r) {
        $spreadsheet = ExcelFactory::load(storage_path('PREGNANCYTRACKING1.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        $sRow = 3;

        foreach($brgy_list as $ind => $b) {
            $sheet->setCellValue('A'.$sRow, $b->brgyName);

            for($i=1;$i<=12;$i++) {
                $month_count1 = PregnancyTrackingForm::whereMonth('created_at', $i)
                ->where('catchment_brgy', $b->brgyName)
                ->whereBetween('age', [10,14])
                ->count();

                $month_count2 = PregnancyTrackingForm::whereMonth('created_at', $i)
                ->where('catchment_brgy', $b->brgyName)
                ->whereBetween('age', [15,19])
                ->count();

                $month_count3 = PregnancyTrackingForm::whereMonth('created_at', $i)
                ->where('catchment_brgy', $b->brgyName)
                ->where('age', '>=', 20)
                ->count();

                if($i == 1) {
                    $row1 = 'B';
                    $row2 = 'C';
                    $row3 = 'D';
                }
                else if($i == 2) {
                    $row1 = 'E';
                    $row2 = 'F';
                    $row3 = 'G';
                }
                else if($i == 3) {
                    $row1 = 'H';
                    $row2 = 'I';
                    $row3 = 'J';
                }
                else if($i == 4) {
                    $row1 = 'N';
                    $row2 = 'O';
                    $row3 = 'P';
                }
                else if($i == 5) {
                    $row1 = 'Q';
                    $row2 = 'R';
                    $row3 = 'S';
                }
                else if($i == 6) {
                    $row1 = 'T';
                    $row2 = 'U';
                    $row3 = 'V';
                }
                else if($i == 7) {
                    $row1 = 'Z';
                    $row2 = 'AA';
                    $row3 = 'AB';
                }
                else if($i == 8) {
                    $row1 = 'AC';
                    $row2 = 'AD';
                    $row3 = 'AE';
                }
                else if($i == 9) {
                    $row1 = 'AF';
                    $row2 = 'AG';
                    $row3 = 'AH';
                }
                else if($i == 10) {
                    $row1 = 'AL';
                    $row2 = 'AM';
                    $row3 = 'AN';
                }
                else if($i == 11) {
                    $row1 = 'AO';
                    $row2 = 'AP';
                    $row3 = 'AQ';
                }
                else if($i == 12) {
                    $row1 = 'AR';
                    $row2 = 'AS';
                    $row3 = 'AT';
                }

                $sheet->setCellValue($row1.$sRow, $month_count1);
                $sheet->setCellValue($row2.$sRow, $month_count2);
                $sheet->setCellValue($row3.$sRow, $month_count3);
            }

            $sRow++;
        }

        $fileName = 'PREGNANCY_TRACKING_Y'.$r->year.'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
