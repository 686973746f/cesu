<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SelfReports extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'isNewRecord',
        'records_id',
        'patientmsg',
        'lname',
        'fname',
        'mname',
        'gender',
        'bdate',
        'cs',
        'nationality',
        'mobile',
        'phoneno',
        'email',
        'philhealth',
        'isPregnant',
        'ifPregnantLMP',
        'address_houseno',
        'address_street',
        'address_brgy',
        'address_city',
        'address_cityjson',
        'address_province',
        'address_provincejson',
        'occupation',
        'occupation_name',
        'natureOfWork',
        'natureOfWorkIfOthers',
        'worksInClosedSetting',

        'occupation_lotbldg',
        'occupation_street',
        'occupation_brgy',
        'occupation_city',
        'occupation_cityjson',
        'occupation_province',
        'occupation_provincejson',
        'occupation_mobile',
        'occupation_email',

        'drunit',
        'drregion',
        'drprovince',
        
        'pType',
        'isHealthCareWorker',
        'healthCareCompanyName',
        'healthCareCompanyLocation',
        'isOFW',
        'OFWCountyOfOrigin',
        'OFWPassportNo',
        'ofwType',
        'isFNT',
        'FNTCountryOfOrigin',
        'FNTPassportNo',
        'isLSI',
        'LSICity',
        'LSICityjson',
        'LSIProvince',
        'LSIProvincejson',
        'lsiType',
        'isLivesOnClosedSettings',
        'institutionType',
        'institutionName',
        'havePreviousCovidConsultation',
        'dateOfFirstConsult',
        'facilityNameOfFirstConsult',
        'dispoType',
        'dispoName',
        'dispoDate',
        'testedPositiveUsingRTPCRBefore',
        'testedPositiveSpecCollectedDate',
        'testedPositiveLab',
        'testedPositiveNumOfSwab',
        'testDateCollected1',
        'testDateReleased1',
        'oniTimeCollected1',
        'testLaboratory1',
        'testType1',
        'testTypeAntigenRemarks1',
        'antigenKit1',
        'testTypeOtherRemarks1',
        'testResult1',
        'testResultOtherRemarks1',
        'vaccinationDate1',
        'vaccinationName1',
        'vaccinationNoOfDose1',
        'vaccinationFacility1',
        'vaccinationRegion1',
        'haveAdverseEvents1',
        'vaccinationDate2',
        'vaccinationName2',
        'vaccinationNoOfDose2',
        'vaccinationFacility2',
        'vaccinationRegion2',
        'haveAdverseEvents2',
        'vaccinationDate3',
        'vaccinationName3',
        'vaccinationNoOfDose3',
        'vaccinationFacility3',
        'vaccinationRegion3',
        'haveAdverseEvents3',
        'vaccinationDate4',
        'vaccinationName4',
        'vaccinationNoOfDose4',
        'vaccinationFacility4',
        'vaccinationRegion4',
        'haveAdverseEvents4',
        'dateOnsetOfIllness',
        'SAS',
        'SASFeverDeg',
        'SASOtherRemarks',
        'COMO',
        'COMOOtherRemarks',
        'diagWithSARI',
        'imagingDoneDate',
        'imagingDone',
        'imagingResult',
        'imagingOtherFindings',
        'expoitem1',
        'expoDateLastCont',
        'expoitem2',
        'intCountry',
        'intDateFrom',
        'intDateTo',
        'intWithOngoingCovid',
        'intVessel',
        'intVesselNo',
        'intDateDepart',
        'intDateArrive',
        'placevisited',
        'locName1',
        'locAddress1',
        'locDateFrom1',
        'locDateTo1',
        'locWithOngoingCovid1',
        'locName2',
        'locAddress2',
        'locDateFrom2',
        'locDateTo2',
        'locWithOngoingCovid2',
        'locName3',
        'locAddress3',
        'locDateFrom3',
        'locDateTo3',
        'locWithOngoingCovid3',
        'locName4',
        'locAddress4',
        'locDateFrom4',
        'locDateTo4',
        'locWithOngoingCovid4',
        'locName5',
        'locAddress5',
        'locDateFrom5',
        'locDateTo5',
        'locWithOngoingCovid5',
        'locName6',
        'locAddress6',
        'locDateFrom6',
        'locDateTo6',
        'locWithOngoingCovid6',
        'locName7',
        'locAddress7',
        'locDateFrom7',
        'locDateTo7',
        'locWithOngoingCovid7',
        'localVessel1',
        'localVesselNo1',
        'localOrigin1',
        'localDateDepart1',
        'localDest1',
        'localDateArrive1',
        'localVessel2',
        'localVesselNo2',
        'localOrigin2',
        'localDateDepart2',
        'localDest2',
        'localDateArrive2',
        'contact1Name',
        'contact1No',
        'contact2Name',
        'contact2No',
        'contact3Name',
        'contact3No',
        'contact4Name',
        'contact4No',
        'remarks',
        'req_file',
        'result_file',
        'senderIP',
        'magicURL',
    ];

    public function getAddress() {
        return $this->address_houseno.', '.$this->address_street.', BRGY.'.$this->address_brgy.', '.$this->address_city.', '.$this->address_province;
    }

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname;
    }
    
    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            if (Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m') == 0) {
                return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%d DAYS');
            }
            else {
                return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
            }
        }
    }

    public function getAgeInt() {
        return Carbon::parse($this->attributes['bdate'])->age;
    }

    public function getType() {
        if($this->pType == 'PROBABLE') {
            return 'SUSPECTED';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'CLOSE CONTACT';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'NON-COVID CASE';
        }
    }

    public function diff4Humans($idate) {
        return Carbon::createFromTimeStamp(strtotime($idate))->diffForHumans();
    }
}
