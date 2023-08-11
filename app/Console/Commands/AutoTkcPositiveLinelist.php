<?php

namespace App\Console\Commands;

use App\Models\Forms;
use App\Mail\TkcDaily;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class AutoTkcPositiveLinelist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autotkc:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AutoTCK Send Linelist Daily';

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
        ->whereIn('caseClassification', ['Suspect', 'Probable', 'Confirmed'])
        ->get();

        if($query->count() != 0) {
            $spreadsheet = IOFactory::load(storage_path('tkc_linelist_template.csv'));
            $sheet = $spreadsheet->getActiveSheet();

            foreach($query as $ind => $d) {
                $ind = $ind + 2;

                $arr_sas = explode(",", $d->SAS);
                $arr_como = explode(",", $d->COMO);
                
                $ocomo = [];

                //Other Comorbids
                if(in_array("Others", $arr_como)) {
                    array_push($ocomo, $d->COMOOtherRemarks);
                }

                if(in_array("Dialysis", $arr_como)) {
                    array_push($ocomo, 'DIALYSIS');
                }
                if(in_array("Operation", $arr_como)) {
                    array_push($ocomo, 'OPERATION');
                }
                if(in_array("Transplant", $arr_como)) {
                    array_push($ocomo, 'TRANSPLANT');
                }

                //Auto Comorbid Pregnant Patients
                if($d->records->isPregnant == 1) {
                    array_push($ocomo, 'PREGNANT');
                }

                $ocomo_final = implode(',', $ocomo);

                //Get Vaccination Status
                if(is_null($d->records->vaccinationDate1)) {
                    $vstatus = 'Unvaccinated';
                }
                else {
                    if(is_null($d->records->vaccinationDate2)) {
                        $vstatus = 'Partially Vaccinated';
                    }
                    else {
                        $vstatus = 'Fully Vaccinated';
                    }
                }

                $sheet->setCellValue('A'.$ind, ''); //report_id
                $sheet->setCellValue('B'.$ind, date('n/d/Y', strtotime($d->created_at))); //created_at
                $sheet->setCellValue('C'.$ind, 'CHRISTIAN JAMES HISTORILLO');
                $sheet->setCellValue('D'.$ind, '1'); //case_tracking_status
                $sheet->setCellValue('E'.$ind, date('n/d/Y', strtotime($d->created_at))); //date_verified
                $sheet->setCellValue('F'.$ind, $d->records->philhealth);
                $sheet->setCellValue('G'.$ind, $d->records->fname);
                $sheet->setCellValue('H'.$ind, $d->records->mname);
                $sheet->setCellValue('I'.$ind, $d->records->lname);
                $sheet->setCellValue('J'.$ind, ''); //suffix
                $sheet->setCellValue('K'.$ind, date('m/d/Y', strtotime($d->records->bdate)));
                $sheet->setCellValue('L'.$ind, strtolower($d->records->gender));
                $sheet->setCellValue('M'.$ind, 'Filipino');
                $sheet->setCellValue('N'.$ind, $d->records->cs);
                $sheet->setCellValue('O'.$ind, $d->records->phoneno);
                $sheet->setCellValue('P'.$ind, substr($d->records->mobile, 1));
                $sheet->setCellValue('Q'.$ind, $d->records->email);
                $sheet->setCellValue('R'.$ind, ''); //area address
                $sheet->setCellValue('S'.$ind, 'house'); //home type
                $sheet->setCellValue('T'.$ind, $d->records->address_houseno);
                $sheet->setCellValue('U'.$ind, ''); //house block
                $sheet->setCellValue('V'.$ind, $d->records->address_street);
                $sheet->setCellValue('W'.$ind, ''); //apartment unit number
                $sheet->setCellValue('X'.$ind, ''); //apartment floor number
                $sheet->setCellValue('Y'.$ind, ''); //apartment_building_name
                $sheet->setCellValue('Z'.$ind, ''); //kit_brand ???
                $sheet->setCellValue('AA'.$ind, ''); //other brands ???
                $sheet->setCellValue('AB'.$ind, $d->records->address_houseno);
                $sheet->setCellValue('AC'.$ind, ''); //current_address_district
                $sheet->setCellValue('AD'.$ind, $d->records->address_street);
                $sheet->setCellValue('AE'.$ind, ''); //current address lat
                $sheet->setCellValue('AF'.$ind, ''); //current address lng
                $sheet->setCellValue('AG'.$ind, $d->records->getRegionPsgc());
                $sheet->setCellValue('AH'.$ind, $d->records->getProvincePsgc());
                $sheet->setCellValue('AI'.$ind, $d->records->getCityPsgc());
                $sheet->setCellValue('AJ'.$ind, $d->records->getBrgyPsgc());
                $sheet->setCellValue('AK'.$ind, ($d->records->hasOccupation == 1) ? $d->records->occupation_name : '');
                $sheet->setCellValue('AL'.$ind, ($d->records->hasOccupation == 1) ? $d->records->occupation_lotbldg : '');
                $sheet->setCellValue('AM'.$ind, ''); //work address lat
                $sheet->setCellValue('AN'.$ind, ''); //work address long
                $sheet->setCellValue('AO'.$ind, ($d->records->hasOccupation == 1) ? $d->records->occupation : '');
                $sheet->setCellValue('AP'.$ind, ($d->records->hasOccupation == 1 && !is_null($d->records->occupation_mobile)) ? $d->records->occupation_mobile : '');
                $sheet->setCellValue('AQ'.$ind, ($d->records->hasOccupation == 1) ? $d->records->getWorkRegionPsgc() : '');
                $sheet->setCellValue('AR'.$ind, ($d->records->hasOccupation == 1) ? $d->records->getWorkProvincePsgc() : '');
                $sheet->setCellValue('AS'.$ind, ($d->records->hasOccupation == 1) ? $d->records->getWorkCityPsgc() : '');
                $sheet->setCellValue('AT'.$ind, ($d->records->hasOccupation == 1) ? $d->records->getWorkBrgyPsgc() : '');
                
                $sheet->setCellValue('AU'.$ind, (is_null($d->SAS)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('AV'.$ind, ($d->diagWithSARI == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('AW'.$ind, (in_array('Fever', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('AX'.$ind, (in_array('Cough', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('AY'.$ind, (in_array('Coryza', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('AZ'.$ind, (in_array('Sore throat', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BA'.$ind, (in_array('Dyspnea', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BB'.$ind, (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BC'.$ind, (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BD'.$ind, (in_array('Myalgia', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BE'.$ind, (in_array('Fatigue', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BF'.$ind, (in_array('General Weakness', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BG'.$ind, (in_array('Headache', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BH'.$ind, (in_array('Diarrhea', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BI'.$ind, (in_array('Nausea', $arr_sas)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BJ'.$ind, (in_array('Altered Mental Status', $arr_sas)) ? 'TRUE' : 'FALSE');
                
                $sheet->setCellValue('BK'.$ind, (!is_null($d->dateOnsetOfIllness) || !is_null($d->SAS)) ? date('m/d/Y', strtotime($d->dateOnsetOfIllness)) : '');
                $sheet->setCellValue('BL'.$ind, (in_array('Hypertension', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BM'.$ind, (in_array('Diabetes', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BN'.$ind, (in_array('Heart Disease', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BO'.$ind, (in_array('Lung Disease', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BP'.$ind, (in_array('Gastrointestinal', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BQ'.$ind, (in_array('Genito-urinary', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BR'.$ind, (in_array('Neurological Disease', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BS'.$ind, (in_array('Cancer', $arr_como)) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('BT'.$ind, 'FALSE');
                $sheet->setCellValue('BU'.$ind, 'FALSE');
                $sheet->setCellValue('BV'.$ind, 'FALSE');
                $sheet->setCellValue('BW'.$ind, 'FALSE');
                $sheet->setCellValue('BX'.$ind, 'FALSE');
                $sheet->setCellValue('BY'.$ind, 'FALSE');

                $sheet->setCellValue('BZ'.$ind, $ocomo_final);
                $sheet->setCellValue('CA'.$ind, ($d->isHealthCareWorker == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CB'.$ind, ($d->isHealthCareWorker == 1) ? $d->healthCareCompanyName : '');
                $sheet->setCellValue('CC'.$ind, ($d->isHealthCareWorker == 1) ? $d->healthCareCompanyLocation : '');
                $sheet->setCellValue('CD'.$ind, ($d->isOFW == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CE'.$ind, ($d->isOFW == 1) ? $d->OFWCountyOfOrigin : '');
                $sheet->setCellValue('CF'.$ind, ($d->isFNT == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CG'.$ind, ($d->isFNT == 1) ? $d->FNTCountryOfOrigin : '');
                $sheet->setCellValue('CH'.$ind, ($d->isLSI == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CI'.$ind, ($d->isLSI == 1) ? $d->LSICity : '');
                $sheet->setCellValue('CJ'.$ind, ($d->isLivesOnClosedSettings == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CK'.$ind, ($d->isLivesOnClosedSettings == 1) ? $d->institutionType : '');
                $sheet->setCellValue('CL'.$ind, ($d->isLivesOnClosedSettings == 1) ? $d->institutionName : '');
                $sheet->setCellValue('CM'.$ind, ''); //illness pregnancy
                $sheet->setCellValue('CN'.$ind, ($d->dispoType == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('CO'.$ind, $d->getTkcAdmittedType());
                $sheet->setCellValue('CP'.$ind, ''); //reason_for_hospitalization
                $sheet->setCellValue('CQ'.$ind, $d->getTkcTreatmentFacility());
                $sheet->setCellValue('CR'.$ind, $d->getTkcQuarantineStatus());
                $sheet->setCellValue('CS'.$ind, ($d->dispoType == 1) ? date('m/d/Y', strtotime($d->dispoDate)) : '');
                $sheet->setCellValue('CT'.$ind, ($d->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($d->outcomeRecovDate)) : '');

                $sheet->setCellValue('CU'.$ind, ''); //quarantine_place
                $sheet->setCellValue('CV'.$ind, ''); //treatr_code
                $sheet->setCellValue('CW'.$ind, ''); //treatp_code
                $sheet->setCellValue('CX'.$ind, ''); //treatm_code

                $sheet->setCellValue('CY'.$ind, $d->healthStatus);
                $sheet->setCellValue('CZ'.$ind, $d->drunit);

                $sheet->setCellValue('DA'.$ind, ''); //nonhealth dru
                $sheet->setCellValue('DB'.$ind, ''); //sentinel reporting unit
                $sheet->setCellValue('DC'.$ind, $d->getTkcVerAssessment());
                $sheet->setCellValue('DD'.$ind, $d->getTkcOutcome());
                $sheet->setCellValue('DE'.$ind, ($d->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($d->outcomeRecovDate)) : '');
                $sheet->setCellValue('DF'.$ind, ($d->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($d->outcomeDeathDate)) : '');
                $sheet->setCellValue('DG'.$ind, ($d->outcomeCondition == 'Died') ? $d->deathImmeCause : '');
                $sheet->setCellValue('DH'.$ind, ($d->outcomeCondition == 'Died') ? $d->deathAnteCause : '');
                $sheet->setCellValue('DI'.$ind, ($d->outcomeCondition == 'Died') ? $d->deathUndeCause : '');
                $sheet->setCellValue('DJ'.$ind, ($d->outcomeCondition == 'Died') ? $d->contriCondi : '');
                $sheet->setCellValue('DK'.$ind, 'ENCODER: '.$d->user->name);
                $sheet->setCellValue('DL'.$ind, ''); //source_report_id
                $sheet->setCellValue('DM'.$ind, ''); //exposure_datetime
                $sheet->setCellValue('DN'.$ind, ''); //exposure_address
                $sheet->setCellValue('DO'.$ind, ''); //nature_of_exposure
                $sheet->setCellValue('DP'.$ind, ''); //exposure remarks
                $sheet->setCellValue('DQ'.$ind, ''); //traced
                $sheet->setCellValue('DR'.$ind, ''); //traced_date
                $sheet->setCellValue('DS'.$ind, ''); //advised
                $sheet->setCellValue('DT'.$ind, ''); //advised_date
                $sheet->setCellValue('DU'.$ind, ''); //local travel address
                $sheet->setCellValue('DV'.$ind, 'TRUE'); //has community transmission
                $sheet->setCellValue('DW'.$ind, ''); //community transmission address
                $sheet->setCellValue('DX'.$ind, ''); //last conctact date
                $sheet->setCellValue('DY'.$ind, 'Unavailable'); //has exposure
                $sheet->setCellValue('DZ'.$ind, ''); //close contact name

                //$sheet->setCellValue('EA'.$ind, $d->getTkcNatureOfExposure()); //nature of exposure
                $sheet->setCellValue('EA'.$ind, ''); //nature of exposure
                $sheet->setCellValue('EB'.$ind, ''); //reason of contact
                $sheet->setCellValue('EC'.$ind, $d->getTkcHasExposure()); //hasExposure
                $sheet->setCellValue('ED'.$ind, $d->getTkcLabInfoType()); //lab_info_type
                $sheet->setCellValue('EE'.$ind, $d->getTkcLab()); //lab name
                $sheet->setCellValue('EF'.$ind, ''); //specimen id
                $sheet->setCellValue('EG'.$ind, $d->getTkcOtherTest()); //testing_others
                $sheet->setCellValue('EH'.$ind, $d->getTkcLabResult());
                $sheet->setCellValue('EI'.$ind, $d->getTkcDateCollected());
                $sheet->setCellValue('EJ'.$ind, ''); //date specimen received
                $sheet->setCellValue('EK'.$ind, $d->getTkcDateResult()); //
                $sheet->setCellValue('EL'.$ind, $d->getTkcAntigenKit());
                $sheet->setCellValue('EM'.$ind, 'FALSE'); //is gida
                $sheet->setCellValue('EN'.$ind, ($d->isLivesOnClosedSettings == 1) ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('EO'.$ind, '');
                $sheet->setCellValue('EP'.$ind, ($d->caseClassification == 'Probable') ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('EQ'.$ind, ($d->caseClassification == 'Suspect') ? 'TRUE' : 'FALSE');
                $sheet->setCellValue('ER'.$ind, ''); //has other reasons
                $sheet->setCellValue('ES'.$ind, ''); //rayson
                $sheet->setCellValue('ET'.$ind, $d->getTkcInvestigationAction()); //investigation action
                $sheet->setCellValue('EU'.$ind, ''); //investigation date
                $sheet->setCellValue('EV'.$ind, ''); //investigation remarks
                $sheet->setCellValue('EW'.$ind, ''); //investigation by user id
                $sheet->setCellValue('EX'.$ind, '');
                $sheet->setCellValue('EY'.$ind, (!is_null($d->records->vaccinationDate1)) ? 'YES' : 'NO'); //is vaccinated
                $sheet->setCellValue('EZ'.$ind, $vstatus);

                $sheet->setCellValue('FA'.$ind, $d->getTkcVaccinationName());
                $sheet->setCellValue('FB'.$ind, $d->getTkcVaccinationDate());
                $sheet->setCellValue('FC'.$ind, $d->getTkcVaccineNumberOfDose('R'));
                $sheet->setCellValue('FD'.$ind, $d->getTkcVaccineNumberOfDose('N'));
                $sheet->setCellValue('FE'.$ind, ''); //vaccination facility id
                $sheet->setCellValue('FF'.$ind, ''); //vaccination facility r code
                $sheet->setCellValue('FG'.$ind, ''); //vaccination facility p code
                $sheet->setCellValue('FH'.$ind, ''); //vaccination facility m code
                $sheet->setCellValue('FI'.$ind, ''); //vaccination facility b code
                $sheet->setCellValue('FJ'.$ind, ''); //other facility
                $sheet->setCellValue('FK'.$ind, ''); //workplace_id
            }
            
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(','); // You can set your delimiter here
            $writer->setEnclosure('"'); // You can set your enclosure here

            $writer->save(public_path('TKC_'.date('mdY').'.csv'));

            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new TkcDaily());
        }

        File::delete(public_path('TKC_'.date('mdY', strtotime('-1 Day')).'.csv'));
    }
}
