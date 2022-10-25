@extends('layouts.app')

@section('content')
<form action="{{route('mp.storecif', ['record_id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><b>New Monkeypox Case Investigation Form (CIF)</b></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="dru_name">Name of DRU</label>
                          <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', 'CHO GENERAL TRIAS')}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dru_region">Region of DRU</label>
                            <input type="text"class="form-control" name="dru_region" id="dru_region" value="{{old('dru_region', 'IV-A')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_province">Province of DRU</label>
                            <input type="text"class="form-control" name="dru_province" id="dru_province" value="{{old('dru_province', 'CAVITE')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_muncity">Municipality/City of DRU</label>
                            <input type="text"class="form-control" name="dru_muncity" id="dru_muncity" value="{{old('dru_muncity', 'GENERAL TRIAS')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_street">Street of DRU</label>
                            <input type="text"class="form-control" name="dru_street" id="dru_street" value="{{old('dru_street', 'PRIA RD')}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_investigation">Date of Investigation</label>
                            <input type="date"class="form-control" name="date_investigation" id="date_investigation" value="{{old('date_investigation')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type of DRU</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="C/MHO" {{(old('type') == 'C/MHO') ? 'selected' : ''}}>C/MHO</option>
                                <option value="GOVT HOSPITAL" {{(old('type') == 'GOVT HOSPITAL') ? 'selected' : ''}}>GOVT HOSPITAL</option>
                                <option value="PRIVATE HOSPITAL" {{(old('type') == 'PRIVATE HOSPITAL') ? 'selected' : ''}}>PRIVATE HOSPITAL</option>
                                <option value="AIRPORT" {{(old('type') == 'AIRPORT') ? 'selected' : ''}}>AIRPORT</option>
                                <option value="SEAPORT" {{(old('type') == 'SEAPORT') ? 'selected' : ''}}>SEAPORT</option>
                                <option value="GOVT LABORATORY" {{(old('type') == 'GOVT LABORATORY') ? 'selected' : ''}}>GOVT LABORATORY</option>
                                <option value="PRIVATE LABORATORY" {{(old('type') == 'PRIVATE LABORATORY') ? 'selected' : ''}}>PRIVATE LABORATORY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_name">Name of Informant</label>
                            <input type="text"class="form-control" name="informant_name" id="informant_name" value="{{old('informant_name')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_relationship">Relationship with Patient</label>
                            <input type="text"class="form-control" name="informant_relationship" id="informant_relationship" value="{{old('informant_relationship')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_contactnumber">Contact No. of Informant</label>
                            <input type="text"class="form-control" name="informant_contactnumber" id="informant_contactnumber" value="{{old('informant_contactnumber')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>II. PATIENT STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_admitted">Date Admitted/Seen/Consult</label>
                                    <input type="date"class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="admission_er">Admitted ER</label>
                                    <select class="form-control" name="admission_er" id="admission_er" required>
                                        <option value="N" {{(old('admission_er') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_er') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_er') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_ward">Admitted Ward</label>
                                    <select class="form-control" name="admission_ward" id="admission_ward" required>
                                        <option value="N" {{(old('admission_ward') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_ward') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_ward') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_icu">Admitted ER</label>
                                    <select class="form-control" name="admission_icu" id="admission_icu" required>
                                        <option value="N" {{(old('admission_icu') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_icu') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_icu') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="other_medicalinformation">Any other known medical information</label>
                                    <input type="text"class="form-control" name="other_medicalinformation" id="other_medicalinformation" value="{{old('other_medicalinformation')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion">Blood Donation/Transfusion History</label>
                                    <select class="form-control" name="ifhashistory_blooddonation_transfusion" id="ifhashistory_blooddonation_transfusion">
                                        <option value="" {{(old('ifhashistory_blooddonation_transfusion') == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="DONOR" {{(old('ifhashistory_blooddonation_transfusion') == 'DONOR') ? 'selected' : ''}}>DONOR</option>
                                        <option value="RECIPIENT" {{(old('ifhashistory_blooddonation_transfusion') == 'RECIPIENT') ? 'selected' : ''}}>RECIPIENT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion_place">Place of Donation/Transfusion</label>
                                    <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_place" id="ifhashistory_blooddonation_transfusion_place" value="{{old('ifhashistory_blooddonation_transfusion_place')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion_date">Date of Donation/Transfusion</label>
                                    <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_date" id="ifhashistory_blooddonation_transfusion_date" value="{{old('ifhashistory_blooddonation_transfusion_date')}}" max="{{date('Y-m-d')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>III. CLINICAL HISTORY/PRESENTATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_onsetofillness">Date onset of illness</label>
                                    <input type="date"class="form-control" name="date_onsetofillness" id="date_onsetofillness" value="{{old('date_onsetofillness')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="have_cutaneous_rash">1. Does the patient have a cutaneous rash?</label>
                                    <select class="form-control" name="have_cutaneous_rash" id="have_cutaneous_rash" required>
                                        <option value="N" {{(old('have_cutaneous_rash') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_cutaneous_rash') == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_have_cutaneous_rash">
                                    <label for="have_cutaneous_rash_date">If yes, date of onset for the rash</label>
                                    <input type="date"class="form-control" name="have_cutaneous_rash_date" id="have_cutaneous_rash_date" value="{{old('have_cutaneous_rash_date')}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="have_fever">2. Did the patient have fever?</label>
                                    <select class="form-control" name="have_fever" id="have_fever" required>
                                        <option value="N" {{(old('have_fever') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_fever') == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_have_fever" class="d-none">
                                    <div class="form-group">
                                        <label for="have_fever_date">If yes, date of onset for the fever</label>
                                        <input type="date"class="form-control" name="have_fever_date" id="have_fever_date" value="{{old('have_fever_date')}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="have_fever_days_duration">Duration of fever (Days)</label>
                                        <input type="date"class="form-control" name="have_fever_days_duration" id="have_fever_days_duration" value="{{old('have_fever_days_duration')}}" min="1" max="99">
                                    </div>
                                </div>
                                <label for="have_fever_date">3. If there is active disease,</label>
                                <ul>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samestate">3.1 Lesions are in the same state of development on the body?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samestate" id="have_activedisease_lesion_samestate" required>
                                                <option value="N" {{(old('have_activedisease_lesion_samestate') == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samestate') == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samesize">3.2 Are all of the lesions the same size?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samesize" id="have_activedisease_lesion_samesize" required>
                                                <option value="N" {{(old('have_activedisease_lesion_samesize') == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samesize') == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_deep">3.3 Are the lesions deep and profound?</label>
                                            <select class="form-control" name="have_activedisease_lesion_deep" id="have_activedisease_lesion_deep" required>
                                                <option value="N" {{(old('have_activedisease_lesion_deep') == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_deep') == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_develop_ulcers">3.4. Did the patient develop ulcers?</label>
                                            <select class="form-control" name="have_activedisease_develop_ulcers" id="have_activedisease_develop_ulcers" required>
                                                <option value="N" {{(old('have_activedisease_develop_ulcers') == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_develop_ulcers') == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_type">4. Type of lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_type[]" id="have_activedisease_lesion_type" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_type') == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="MACULE" {{(old('have_activedisease_lesion_type') == 'MACULE') ? 'selected' : ''}}>MACULE</option>
                                        <option value="PAPULE" {{(old('have_activedisease_lesion_type') == 'PAPULE') ? 'selected' : ''}}>PAPULE</option>
                                        <option value="VESICLE" {{(old('have_activedisease_lesion_type') == 'VESICLE') ? 'selected' : ''}}>VESICLE</option>
                                        <option value="PUSTULE" {{(old('have_activedisease_lesion_type') == 'PUSTULE') ? 'selected' : ''}}>PUSTULE</option>
                                        <option value="SCAB" {{(old('have_activedisease_lesion_type') == 'SCAB') ? 'selected' : ''}}>SCAB</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization">5. Localization of the lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_localization" id="have_activedisease_lesion_localization" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_localization') == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="FACE" {{(old('have_activedisease_lesion_localization') == 'FACE') ? 'selected' : ''}}>FACE</option>
                                        <option value="PALMS OF THE HANDS" {{(old('have_activedisease_lesion_localization') == 'PALMS OF THE HANDS') ? 'selected' : ''}}>PALMS OF THE HANDS</option>
                                        <option value="THORAX" {{(old('have_activedisease_lesion_localization') == 'THORAX') ? 'selected' : ''}}>THORAX</option>
                                        <option value="ARMS" {{(old('have_activedisease_lesion_localization') == 'ARMS') ? 'selected' : ''}}>ARMS</option>
                                        <option value="LEGS" {{(old('have_activedisease_lesion_localization') == 'LEGS') ? 'selected' : ''}}>LEGS</option>
                                        <option value="SOLES OF THE FEET" {{(old('have_activedisease_lesion_localization') == 'SOLES OF THE FEET') ? 'selected' : ''}}>SOLES OF THE FEET</option>
                                        <option value="GENITALS" {{(old('have_activedisease_lesion_localization') == 'GENITALS') ? 'selected' : ''}}>GENITALS</option>
                                        <option value="ALL OVER THE BODY" {{(old('have_activedisease_lesion_localization') == 'ALL OVER THE BODY') ? 'selected' : ''}}>ALL OVER THE BODY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization_otherareas">List other Areas</label>
                                    <input type="text"class="form-control" name="have_activedisease_lesion_localization_otherareas" id="have_activedisease_lesion_localization_otherareas" value="{{old('have_activedisease_lesion_localization_otherareas')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="symptoms_list">Signs and Symptoms (Select all that apply)</label>
                                    <select class="form-control" name="symptoms_list[]" id="symptoms_list" multiple required>
                                        <option value="" disabled {{(old('symptoms_list') == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="VOMITING/NAUSEA" {{(old('symptoms_list') == 'VOMITING/NAUSEA') ? 'selected' : ''}}>VOMITING/NAUSEA</option>
                                        <option value="HEADACHE" {{(old('symptoms_list') == 'HEADACHE') ? 'selected' : ''}}>HEADACHEA</option>
                                        <option value="COUGH" {{(old('symptoms_list') == 'COUGH') ? 'selected' : ''}}>COUGH</option>
                                        <option value="MUSCLE PAIN (MYALGIA)" {{(old('symptoms_list') == 'MUSCLE PAIN (MYALGIA)') ? 'selected' : ''}}>MUSCLE PAIN (MYALGIA)</option>
                                        <option value="ASTHENIA (WEAKNESS)" {{(old('symptoms_list') == 'ASTHENIA (WEAKNESS)') ? 'selected' : ''}}>ASTHENIA (WEAKNESS)</option>
                                        <option value="FATIGUE" {{(old('symptoms_list') == 'FATIGUE') ? 'selected' : ''}}>FATIGUE</option>
                                        <option value="CONJUNCTIVITIS" {{(old('symptoms_list') == 'CONJUNCTIVITIS') ? 'selected' : ''}}>CONJUNCTIVITIS</option>
                                        <option value="CHILLS OR SWEATS" {{(old('symptoms_list') == 'CHILLS OR SWEATS') ? 'selected' : ''}}>CHILLS OR SWEATS</option>
                                        <option value="SENSITIVITY TO LIGHT" {{(old('symptoms_list') == 'SENSITIVITY TO LIGHT') ? 'selected' : ''}}>SENSITIVITY TO LIGHT</option>
                                        <option value="SORE THROAT WHEN SWALLOWING" {{(old('symptoms_list') == 'SORE THROAT WHEN SWALLOWING') ? 'selected' : ''}}>SORE THROAT WHEN SWALLOWING</option>
                                        <option value="ORAL ULCERS" {{(old('symptoms_list') == 'ORAL ULCERS') ? 'selected' : ''}}>ORAL ULCERS</option>
                                        <option value="LYMPHADENOPATHY" {{(old('symptoms_list') == 'LYMPHADENOPATHY') ? 'selected' : ''}}>LYMPHADENOPATHY (SPECIFY LOCALIZATION)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="symptoms_lymphadenopathy_localization">Signs and Symptoms (Select all that apply)</label>
                                    <select class="form-control" name="symptoms_lymphadenopathy_localization[]" id="symptoms_lymphadenopathy_localization" multiple required>
                                        <option value="CERVICAL" {{(old('symptoms_lymphadenopathy_localization') == 'CERVICAL') ? 'selected' : ''}}>CERVICAL</option>
                                        <option value="AXILLARY" {{(old('symptoms_lymphadenopathy_localization') == 'AXILLARY') ? 'selected' : ''}}>AXILLARY</option>
                                        <option value="INGUINAL" {{(old('symptoms_lymphadenopathy_localization') == 'INGUINAL') ? 'selected' : ''}}>INGUINAL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>IV. HISTORY OF EXPOSURE</b></div>
                    <div class="card-body">

                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>V. LABORATORY TEST</b> <small><i>(Note: Collect at least two types of specimens from each patient. For each specimen: place a label on this form and a label on the specimen tube. Ensure that the two labels have the same name/number of the specimen.)</i></small></div>
                    <div class="card-body">

                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>VI. HEALTH STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="health_status">Current Health Status</label>
                                    <select class="form-control" name="health_status" id="health_status" required>
                                        <option value="ACTIVE" {{(old('health_status') == 'ACTIVE') ? 'selected' : ''}}>ACTIVE</option>
                                        <option value="DISCHARGED" {{(old('health_status') == 'DISCHARGED') ? 'selected' : ''}}>DISCHARGED</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_health_status_date_discharged">
                                    <label for="health_status_date_discharged">Date Discharged</label>
                                    <input type="date"class="form-control" name="health_status_date_discharged" id="health_status_date_discharged" value="{{old('health_status_date_discharged')}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="health_status_final_diagnosis">Final Diagnosis</label>
                                    <input type="text"class="form-control" name="health_status_final_diagnosis" id="health_status_final_diagnosis" value="{{old('health_status_final_diagnosis')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outcome">Current Health Status</label>
                                    <select class="form-control" name="outcome" id="outcome" required>
                                        <option value="RECOVERED" {{(old('outcome') == 'RECOVERED') ? 'selected' : ''}}>RECOVERED</option>
                                        <option value="DIED" {{(old('outcome') == 'DIED') ? 'selected' : ''}}>DIED</option>
                                        <option value="UNKNOWN" {{(old('outcome') == 'UNKNOWN') ? 'selected' : ''}}>UNKNOWN</option>
                                        <option value="TRANSFERRED TO OTHER HEALTHCARE SETTING" {{(old('outcome') == 'TRANSFERRED TO OTHER HEALTHCARE SETTING') ? 'selected' : ''}}>TRANSFERRED TO OTHER HEALTHCARE SETTING</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_outcome_date_recovered">
                                    <label for="outcome_date_recovered">Date Recovered</label>
                                    <input type="date"class="form-control" name="outcome_date_recovered" id="outcome_date_recovered" value="{{old('outcome_date_recovered')}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div id="div_outcome_date_died" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_date_died">Date Died</label>
                                        <input type="date"class="form-control" name="outcome_date_died" id="outcome_date_died" value="{{old('outcome_date_recovered')}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="outcome_causeofdeath">Cause of death</label>
                                        <input type="text"class="form-control" name="outcome_causeofdeath" id="outcome_causeofdeath" value="{{old('outcome_causeofdeath')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div id="div_outcome_unknown_type" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_unknown_type">Type</label>
                                        <select class="form-control" name="outcome_unknown_type" id="outcome_unknown_type" required>
                                            <option value="HAMA" {{(old('outcome_unknown_type') == 'HAMA') ? 'selected' : ''}}>HAMA</option>
                                            <option value="LOST TO FOLLOW-UP" {{(old('outcome_unknown_type') == 'LOST TO FOLLOW-UP') ? 'selected' : ''}}>LOST TO FOLLOW-UP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="case_classification">Case Classification</label>
                                    <select class="form-control" name="case_classification" id="case_classification" required>
                                        <option value="SUSPECT" {{(old('case_classification') == 'SUSPECT') ? 'selected' : ''}}>SUSPECT</option>
                                        <option value="PROBABLE" {{(old('case_classification') == 'PROBABLE') ? 'selected' : ''}}>PROBABLE</option>
                                        <option value="CONFIRMED" {{(old('case_classification') == 'CONFIRMED') ? 'selected' : ''}}>CONFIRMED</option>
                                        <option value="CONTACT" {{(old('case_classification') == 'CONTACT') ? 'selected' : ''}}>CONTACT</option>
                                        <option value="DISCARDED" {{(old('case_classification') == 'HAMA') ? 'selected' : ''}}>DISCARDED</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#have_cutaneous_rash').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_cutaneous_rash').removeClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', true);
        }
        else {
            $('#div_have_cutaneous_rash').addClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', false);
        }
    });

    $('#have_fever').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_fever').removeClass('d-none');
            $('#have_fever_date').prop('required', true);
            $('#have_fever_days_duration').prop('required', true);
        }
        else {
            $('#div_have_fever').addClass('d-none');
            $('#have_fever_date').prop('required', false);
            $('#have_fever_days_duration').prop('required', false);
        }
    });

    $('#health_status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'ACTIVE') {
            $('#div_health_status_date_discharged').addClass('d-none');
            $('#health_status_date_discharged').prop('required', false);
        }
        else if($(this).val() == 'DISCHARGED') {
            $('#div_health_status_date_discharged').removeClass('d-none');
            $('#health_status_date_discharged').prop('required', true);
        }
    });
</script>
@endsection