<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use App\Mail\SendCovidDatabase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class AutoEmailCovidDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoemailcoviddatabase:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Email Covid Databse Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        DB::setDefaultConnection('mysqlcesuexp');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', 900);

        $newlyencoded_count = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();

        $suspectedQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->where('caseClassification', 'Suspect')
        ->where('outcomeCondition', 'Active')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')]);

        $probableQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Probable')
        ->where('outcomeCondition', 'Active')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')]);

        $confirmedQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

        $negativeQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Non-COVID-19 Case')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

        $suspectedQuery = $suspectedQuery->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        });

        $probableQuery = $probableQuery->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        });

        $confirmedQuery = $confirmedQuery->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        });

        $negativeQuery = $negativeQuery->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        });

        $suspectedQuery = $suspectedQuery->orderby('morbidityMonth', 'asc');
        $probableQuery = $probableQuery->orderby('morbidityMonth', 'asc');
        $confirmedQuery = $confirmedQuery->orderby('morbidityMonth', 'asc');
        $negativeQuery = $negativeQuery->orderby('morbidityMonth', 'asc');
        
        function suspectedGenerator($suspectedQuery) {
            foreach ($suspectedQuery->cursor() as $user) {
                yield $user;
            }
        }

        function probableGenerator($probableQuery) {
            foreach ($probableQuery->cursor() as $user) {
                yield $user;
            }
        }

        function confirmedGenerator($confirmedQuery) {
            foreach ($confirmedQuery->cursor() as $user) {
                yield $user;
            }
        }

        function negativeGenerator($negativeQuery) {
            foreach ($negativeQuery->cursor() as $user) {
                yield $user;
            }
        }
        
        $sheets = new SheetCollection([
            'Suspected' => suspectedGenerator($suspectedQuery),
            'Probable' => probableGenerator($probableQuery),
            'Confirmed' => confirmedGenerator($confirmedQuery),
            'Negative' => negativeGenerator($negativeQuery),
        ]);

        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        $exp = (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->export(public_path('GENTRI_COVID19_DATABASE_'.date('m_d_Y').'.xlsx'), function ($form) {
            $arr_sas = explode(",", $form->SAS);
            $arr_othersas = explode(",", $form->SASOtherRemarks);
            $arr_como = explode(",", $form->COMO);

            if(is_null($form->testType2)) {
                $testType = $form->testType1;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
                $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
                $testResult = $form->testResult1;
            }
            else {
                //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
                $testType = $form->testType2;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
                $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
                $testResult = $form->testResult2;
            }

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

            if($form->outcomeCondition == 'Recovered') {
                if($form->dispoType == 3) {
                    $dispo = 'CLEARED';
                }
                else {
                    $dispo = 'DISCHARGED';
                }
            }

            //Vaccination Facility
            if(!is_null($form->records->vaccinationDate2)) {
                if($form->records->vaccinationFacility2) {
                    $vFacility = $form->records->vaccinationFacility2;
                }
                else {
                    $vFacility = 'N/A';
                }
            }
            else {
                if($form->records->vaccinationFacility1) {
                    $vFacility = $form->records->vaccinationFacility1;
                }
                else {
                    $vFacility = 'N/A';
                }
            }

            //Remarks
            if($form->reinfected == 1) {
                if(!is_null($form->remarks)) {
                    $remarks = 'REINFECTED | '.$form->remarks;
                }
                else {
                    $remarks = 'REINFECTED';
                }
            }
            else {
                if(!is_null($form->remarks)) {
                    $remarks = $form->remarks;
                }
                else {
                    $remarks = 'N/A';
                }
            }

            return [
                'MM (Morbidity Month)' => date('m/d/Y', strtotime($form->morbidityMonth)),
                'MW (Morbidity Week)' => Carbon::parse($form->morbidityMonth)->format('W'),
                'DATE REPORTED' => date('m/d/Y', strtotime($form->dateReported)),
                'DRU' => $form->drunit,
                'REGION OF DRU' => $form->drregion,
                'MUNCITY OF DRU' => $form->drprovince,
                'LAST NAME' => $form->records->lname,
                'FIRST NAME' => $form->records->fname,
                'MIDDLE NAME' => (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
                'DOB' => date('m/d/Y', strtotime($form->records->bdate)),
                'AGE (AGE IN YEARS)' => $form->records->getAge(),
                'SEX(M/F)' => substr($form->records->gender,0,1),
                'NATIONALITY' => $form->records->nationality,
                'REGION' => 'IV A',
                'PROVINCE/HUC' => $form->records->address_province,
                'MUNICIPALITY/CITY' => $form->records->address_city,
                'BARANGAY' => $form->records->address_brgy,
                'HOUSE N. AND STREET OR NEAREST LANDMARK' => $form->records->address_houseno.', '.$form->records->address_street,
                'CONTACT N.' => ($form->records->mobile != '09190664324') ? $form->records->mobile : 'N/A',
                'OCCUPATION' => (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
                'HEALTHCARE WORKER(Y/N)' => ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
                'PLACE OF WORK' => ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
                'SEVERITY OF THE CASE (ASYMTOMATIC,MILD,MODERATE,SEVERE,CRITICAL)' => $form->healthStatus,
                'PREGNANT (Y/N)' => ($form->records->isPregnant == 1) ? 'Y' : 'N',
                'ONSET OF ILLNESS' => (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
                'FEVER(Y/N)' => (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
                'COUGH (Y/N)' => (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
                'COLDS (Y/N)' => (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas)) ? 'Y' : 'N',
                'DOB (Y/N)' => (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
                'LOSS OF SMELL (Y/N)' => (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
                'LOSS OF TASTE (Y/N)' => (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
                'SORETHROAT (Y/N)' => (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
                'DIARRHEA (Y/N)' => (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
                'OTHER SYMPTOMS' => (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
                'W. COMORBIDITY (Y/N)' => ($form->COMO != 'None') ? 'Y' : 'N',
                'COMORBIDITY (HYPERTENSIVE, DIABETIC, WITH HEART PROBLEM, AND OTHERS)' => ($form->COMO != 'None') ? $form->COMO : 'N/A',
                'DATE OF SPECIMEN COLLECTION' => $testDate,
                'ANTIGEN (POSITIVE/NEGATIVE)' => ($testType == 'ANTIGEN') ? $testResult : 'N/A',
                'PCR(POSITIVE/NEGATIVE)' => ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
                'RDT(+IGG, +IGM,NEGATIVE)' => ($testType == 'ANTIBODY') ? $testResult : 'N/A',
                'CLASSIFICATION (CONFIRMED,SUSPECTED,PROBABLE,FOR VALIDATION)' => $form->caseClassification,
                'QUARANTINE STATUS (ADMITTED,HOME QUARANTINE,TTMF,CLEARED,DISCHARGED)' => $dispo,
                'NAME OF FACILITY (FOR FACILITY QUARANTINE AND ADMITTED)' => $dispoName,
                'DATE START OF QUARANTINE' => $dispoDate,
                'DATE COMPLETED QUARANTINE (FOR HOME AND FACILITY QUARANTINE)' => ($form->dispoType == 4) ? $dispoDate : 'N/A',
                'OUTCOME(ALIVE/RECOVERED/DIED)' => $form->outcomeCondition,
                'DATE RECOVERED' => ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
                'DATE DIED' => ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
                'CAUSE OF DEATH' => ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
                'W. TRAVEL HISTORY(Y/N)' => ($form->expoitem2 == 1) ? 'Y' : 'N',
                'PLACE OF TRAVEL' => (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
                'DATE OF TRAVEL' => (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
                'LSI (Y/N)' => ($form->isLSI == 1) ? 'Y' : 'N',
                'ADDRESS(LSI)' => ($form->isLSI == 1) ? $form->LSICity : 'N/A',
                'OFW(Y/N)' => ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
                'PLACE OF ORIGIN (OFW)' => ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
                'DATE OF ARRIVAL (OFW)' => "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS,
                'AUTHORIZED PERSON OUTSIDE RESIDENCE (Y/N)' => ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
                'LOCAL/IMPORTED CASE' => "UNKNOWN",
                'RETURNING OVERSEAS FILIPINO (Y/N)' => ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
                'REMARKS' => $remarks,
                'VACCINATED (Y/N)' => (!is_null($form->records->vaccinationDate1)) ? 'Y' : 'N',
                'VACCINE' => (!is_null($form->records->vaccinationDate1)) ? $form->records->vaccinationName1 : 'N/A',
                '1ST DOSE (DATE)' => (!is_null($form->records->vaccinationDate1)) ? date('m/d/Y', strtotime($form->records->vaccinationDate1)) : 'N/A',
                '2ND DOSE (DATE)' => (!is_null($form->records->vaccinationDate2)) ? date('m/d/Y', strtotime($form->records->vaccinationDate2)) : 'N/A',
                'NAME OF FACILITY' => $vFacility,
                '1ST BOOSTER NAME' => (!is_null($form->records->vaccinationDate3)) ? $form->records->vaccinationName3 : 'N/A',
                '1ST BOOSTER DATE' => (!is_null($form->records->vaccinationDate3)) ? $form->records->vaccinationDate3 : 'N/A',
                '2ND BOOSTER NAME' => (!is_null($form->records->vaccinationDate4)) ? $form->records->vaccinationName4 : 'N/A',
                '2ND BOOSTER DATE' => (!is_null($form->records->vaccinationDate4)) ? $form->records->vaccinationDate4 : 'N/A',
                'YEAR' => date('Y', strtotime($form->dateReported)),
            ];
        });

        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com', 'pesucavite@gmail.com', 'resu4a@gmail.com', 'ludettelontoc@gmail.com', 'macvillaviray.doh@gmail.com', 'cavitecovid19labresults@gmail.com'])->send(new SendCovidDatabase($newlyencoded_count));

        File::delete(public_path('GENTRI_COVID19_DATABASE_'.date('m_d_Y', strtotime('-1 Day')).'.xlsx'));
    }
}
