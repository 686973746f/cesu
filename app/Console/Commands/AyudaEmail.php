<?php

namespace App\Console\Commands;

use App\Models\Forms;
use App\Mail\SendAyudaList;
use Illuminate\Console\Command;
use App\Mail\SendAyudaListEmpty;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class AyudaEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ayudaemail:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Ayuda Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed');

        $count = $query->count();

        if($count != 0) {
            function suspectedGenerator($query) {
                foreach ($query->cursor() as $user) {
                    yield $user;
                }
            }
    
            $sheets = new SheetCollection([
                'Ayuda List '.date('m-d-Y') => suspectedGenerator($query),
            ]);
    
            $header_style = (new StyleBuilder())->setFontBold()->build();
            $rows_style = (new StyleBuilder())->setShouldWrapText()->build();
    
            $exp = (new FastExcel($sheets))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export(public_path('AyudaList_'.date('F_d_Y').'.xlsx'), function ($form) {
                $arr_sas = explode(",", $form->SAS);
                $arr_othersas = explode(",", $form->SASOtherRemarks);

                if($form->dispoType == 1) {
                    //HOSPITAL
                    $dispo = 'ADMITTED';
                    $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if($form->dispoType == 2) {
                    //OTHER ISOLATION FACILITY
                    $dispo = 'ADMITTED';
                    $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if($form->dispoType == 3) {
                    //HOME QUARANTINE
                    $dispo = 'HOME QUARANTINE';
                    $dispoName = "N/A";
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if($form->dispoType == 4) {
                    //DISCHARGED TO HOME
                    $dispo = 'DISCHARGED';
                    $dispoName = "N/A";
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if($form->dispoType == 5) {
                    //OTHERS
                    $dispo = 'ADMITTED';
                    $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if($form->dispoType == 6) {
                    //GENTRI ISOLATION FACILITY #1 (SANTIAGO OVAL)
                    $dispo = 'ADMITTED';
                    $dispoName = 'GENERAL TRIAS ISOLATION FACILITY';
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else if ($form->dispoType == 7) {
                    $dispo = 'ADMITTED';
                    $dispoName = 'GENERAL TRIAS ISOLATION FACILITY #2';
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }
                else {
                    $dispo = 'UNKNOWN';
                    $dispoName = "N/A";
                    $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
                }

                return [
                    'DATE REPORTED' => date('m/d/Y', strtotime($form->dateReported)),
                    'DRU' => $form->drunit,
                    'REGION OF DRU' => $form->drregion,
                    'MUNCITY OF DRU' => $form->drprovince,
                    'LAST NAME' => $form->records->lname,
                    'FIRST NAME' => $form->records->fname,
                    'MIDDLE NAME' => (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
                    'DOB' => date('m/d/Y', strtotime($form->records->bdate)),
                    'AGE (IN YEARS)' => $form->records->getAge(),
                    'SEX (M/F)' => substr($form->records->gender,0,1),
                    'NATIONALITY' => $form->records->nationality,
                    'REGION' => 'IV A',
                    'PROVINCE/HUC' => $form->records->address_province,
                    'MUNICIPALITY/CITY' => $form->records->address_city,
                    'BARANGAY' => $form->records->address_brgy,
                    'HOUSE N. AND STREET OR NEAREST LANDMARK' => $form->records->address_houseno.', '.$form->records->address_street,
                    'CONTACT N.' => ($form->records->mobile != '09190664324') ? $form->records->mobile : 'N/A',
                    'QUARANTINE STATUS (ADMITTED, HOME QUARANTINE, TTMF, CLEARED, DISCHARGED)' => $dispo,
                    'SEVERITY OF THE CASE (ASYMPTOMATIC, MILD, MODERATE, SEVERE, CRITICAL)' => $form->healthStatus,
                    'PREGNANT (Y/N)' => ($form->records->isPregnant == 1) ? 'Y' : 'N',
                    'ONSET OF ILLNESS' => (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
                    'FEVER (Y/N)' => (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
                    'COUGH (Y/N)' => (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
                    'DOB (Y/N)' => (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
                    'LOSS OF SMELL (Y/N)' => (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
                    'LOSS OF TASTE (Y/N)' => (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
                    'SORE THROAT (Y/N)' => (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
                    'DIARRHEA (Y/N)' => (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
                    'OTHER SYMPTOMS' => (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
                ];
            });

            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'glorybemendez06@gmail.com', 'citymayor.generaltriascavite@gmail.com', 'chogentri2@proton.me'])->send(new SendAyudaList($count));
        }
        else {
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'glorybemendez06@gmail.com', 'citymayor.generaltriascavite@gmail.com', 'chogentri2@proton.me'])->send(new SendAyudaListEmpty());
        }

        File::delete(public_path('AyudaList_'.date('F_d_Y', strtotime('-1 Day')).'.xlsx'));
    }
}