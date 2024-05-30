<?php

namespace App\Models;

use App\Models\Brgy;
use App\Models\Antigen;
use App\Models\Records;
use App\Models\CifUploads;
use App\Models\AbtcPatient;
use App\Models\PaSwabLinks;
use App\Models\LinelistMaster;
use App\Models\PharmacyBranch;
use App\Models\PharmacySupply;
use App\Models\AcceptanceLetter;
use App\Models\SyndromicPatient;
use App\Models\SyndromicRecords;
use App\Models\AbtcBakunaRecords;
use App\Models\SecondaryTertiaryRecords;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/*
GLOBAL_ADMIN

CESU_ADMIN
CESU_ENCODER

CESU_BRGY_ADMIN
CESU_BRGY_ENCODER

ABTC_ADMIN
ABTC_ENCODER

PIDSR_ADMIN
PIDSR_ENCODER

VAXCERT_ADMIN
VAXCERT_ENCODER

FHSIS_ADMIN
FHSIS_ENCODER

ITR_ADMIN
ITR_ENCODER

ITR_HOSPITAL

ITR_BRGY_ADMIN
ITR_BRGY_ENCODER

PHARMACY_ADMIN
PHARMACY_ENCODER

PHARMACY_BRGY_ADMIN
PHARMACY_BRGY_ENCODER

*/

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'isAdmin',
        'brgy_id',
        'company_id',
        'name',
        'email',
        'password',
        'pharmacy_branch_id',
        'itr_facility_id',
        'itr_medicalevent_id',
        'abtc_default_vaccinationsite_id',
        'abtc_default_vaccinebrand_id',
        'abtc_default_vaccinebrand_date',
        'last_login_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function ifTopAdmin() {
        if($this->isAdmin == 1) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function isCesuAccount() {
        if($this->isAdmin == 1 || $this->isAdmin == 2) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isBrgyAccount() {
        if(!is_null($this->brgy_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isCompanyAccount() {
        if(!is_null($this->company_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isLevel1() {
        if($this->isAdmin == 1 || $this->isAdmin == 2 || !is_null($this->company_id) || !is_null($this->brgy_id)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isLevel2() {
        if($this->isAdmin == 3) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isLevel3() {
        if($this->isAdmin == 4) {
            return true;
        }
        else {
            return false;
        }
    }

    

    public function canUseLinelist() {
        if($this->canAccessLinelist == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    public function records() {
        return $this->hasMany(Records::class);
    }

    public function form() {
        return $this->hasMany(Forms::class);
    }

    public function brgy() {
        return $this->belongsTo(Brgy::class);
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }

    public function interviewer() {
        return $this->hasMany(Interviewers::class);
    }
    
    public function acceptanceletter() {
        return $this->hasMany(AcceptanceLetter::class);
    }

    public function syndromicpatient() {
        return $this->hasMany(SyndromicPatient::class, 'created_by');
    }

    public function syndromicrecord() {
        return $this->hasMany(SyndromicRecords::class, 'created_by');
    }
    
    public function pharmacybranch() {
        return $this->belongsTo(PharmacyBranch::class, 'pharmacy_branch_id');
    }

    public function createpharmacybranch() {
        return $this->hasMany(PharmacyBranch::class, 'created_by');
    }

    public function pharmacysupplymaster() {
        return $this->hasMany(PharmacySupplyMaster::class, 'created_by');
    }

    public function pharmacysupplysub() {
        return $this->hasMany(PharmacySupplySub::class, 'created_by');
    }

    public function pharmacysupplysubstock() {
        return $this->hasMany(PharmacySupplySubStock::class, 'created_by');
    }

    public function pharmacystockcard() {
        return $this->hasMany(PharmacyStockCard::class, 'created_by');
    }

    public function pharmacypatient() {
        return $this->hasMany(PharmacyPatient::class, 'created_by');
    }

    public function pharmacycartmain() {
        return $this->hasMany(PharmacyCartMain::class, 'created_by');
    }

    public function pharmacyprescription() {
        return $this->hasMany(PharmacyPrescription::class, 'created_by');
    }

    public function pharmacycartmainbranch() {
        return $this->hasMany(PharmacyCartMainBranch::class, 'created_by');
    }

    public function defaultInterviewer() {
        if(!is_null($this->interviewer_id)) {
            $i = Interviewers::find($this->interviewer_id);
            
            return $i->lname.", ".$i->fname;
        }
        else {
            return null;
        }
    }

    public function linelistmaster() {
        return $this->hasMany(LinelistMasters::class);
    }

    public function cifupload() {
        return $this->hasMany(CifUploads::class);
    }

    public function company() {
        return $this->hasOne(Companies::class);
    }

    public function referralCode() {
        return $this->hasMany(ReferralCodes::class);
    }

    public function paSwabLink() {
        return $this->hasMany(PaSwabLinks::class);
    }

    public function getAccountType() {
        if($this->isAdmin == 1) {
            return 'admin';
        }
        else if($this->isAdmin == 2) {
            return 'encoder';
        }
    }

    public function secondaryTertiaryRecords() {
        return $this->hasMany(SecondaryTertiaryRecords::class);
    }

    public function exposureHistory() {
        return $this->hasMany(ExposureHistory::class);
    }

    public function antigen() {
        return $this->hasMany(Antigen::class);
    }

    public function abtcpatient() {
        return $this->hasMany(AbtcPatient::class, 'created_by');
    }

    public function abtcbakunarecord() {
        return $this->hasMany(AbtcBakunaRecords::class, 'created_by');
    }

    public function abtcvaccinelog() {
        return $this->hasMany(AbtcVaccineLogs::class, 'created_by');
    }

    public function abtcGetDefaultVaccine() {
        $d = AbtcVaccineBrand::find($this->abtc_default_vaccinebrand_id);

        return $d;
    }

    public function pregnancytrackingform() {
        return $this->hasMany(PregnancyTrackingForm::class, 'created_by');
    }

    public function qesmain() {
        return $this->hasMany(QesMain::class, 'created_by');
    }

    public function qessub() {
        return $this->hasMany(QesSub::class, 'created_by');
    }

    public function opdfacility() {
        return $this->belongsTo(DohFacility::class, 'itr_facility_id');
    }

    public function medicalevent() {
        return $this->hasMany(MedicalEvent::class, 'created_by');
    }

    public function getMedicalEvent() {
        return $this->belongsTo(MedicalEvent::class, 'itr_medicalevent_id');
    }

    public function livebirth() {
        return $this->hasMany(LiveBirth::class, 'created_by');
    }

    public function lablogbookpatient() {
        return $this->hasMany(LabResultLogBook::class, 'created_by');
    }

    public function lablogbookgroup() {
        return $this->hasMany(LabResultLogBookGroup::class, 'created_by');
    }

    //perms
    public function getPermissions() {
        return explode(",", $this->permission_list);
    }

    public function canAccessCovid() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('CESU_ADMIN', $plist) || in_array('CESU_ENCODER', $plist) || in_array('CESU_BRGY_ADMIN', $plist) || in_array('CESU_BRGY_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminCovid() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('CESU_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getAccessLevelCovid() {
        
    }

    public function canAccessAbtc() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('ABTC_ADMIN', $plist) || in_array('ABTC_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminAbtc() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('ABTC_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessVaxcert() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('VAXCERT_ADMIN', $plist) || in_array('VAXCERT_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminVaxcert() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('VAXCERT_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessSyndromic() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('ITR_ADMIN', $plist) || in_array('ITR_ENCODER', $plist) || in_array('ITR_BRGY_ADMIN', $plist) || in_array('ITR_BRGY_ENCODER', $plist) || in_array('ITR_HOSPITAL_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isStaffSyndromic() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('ITR_ADMIN', $plist) || in_array('ITR_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isSyndromicBrgyLevelAccess() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('ITR_BRGY_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isSyndromicHospitalLevelAccess() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('ITR_HOSPITAL_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getAccessLevelSyndromic() {
        
    }

    public function isAdminSyndromic() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('ITR_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessFhsis() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('FHSIS_ADMIN', $plist) || in_array('FHSIS_ENCODER', $plist) || in_array('CESU_BRGY_ADMIN', $plist) || in_array('CESU_BRGY_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminFhsis() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('FHSIS_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessPidsr() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('PIDSR_ADMIN', $plist) || in_array('PIDSR_ENCODER', $plist) || in_array('CESU_BRGY_ADMIN', $plist) || in_array('CESU_BRGY_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminPidsr() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('PIDSR_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessPharmacy() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('PHARMACY_ADMIN', $plist) || in_array('PHARMACY_ENCODER', $plist) || in_array('PHARMACY_BRGY_ADMIN', $plist) || in_array('PHARMACY_BRGY_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isAdminPharmacy() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('PHARMACY_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isGlobalAdmin() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessFwri() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('FWRI', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessPregnancyTracking() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('PREGNANCYTRACKING', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function canAccessQes() {
        $plist = $this->getPermissions();

        if(in_array('GLOBAL_ADMIN', $plist) || in_array('QES_ENCODER', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getItrDefaultDoctor() {
        $f = SyndromicDoctor::where('id', $this->itr_doctor_id)->first();

        if($f) {
            return $f;
        }
        else {
            return NULL;
        }
    }
    
    public function ifInitAbtcVaccineBrandDaily() {
        if(date('Y-m-d') != date('Y-m-d', strtotime($this->abtc_default_vaccinebrand_date))) {
            return false;
        }
        else {
            return true;
        }
    }

    public function isMayor() {
        $plist = $this->getPermissions();

        if(in_array('MAYOR_ACCESS', $plist)) {
            return true;
        }
        else {
            return false;
        }
    }
}
