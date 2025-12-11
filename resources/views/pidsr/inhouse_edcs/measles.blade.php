@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report Influenza-Like Illness Case</div>
                </b>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All fields marked with <b class="text-danger">*</b> are required. By filling out this form, the patient agrees to the collection of their data in accordance to the Data Privacy Act of 2012 and Republic Act 11332.
                </div>
                @if(!auth()->check())
                <div class="form-group d-none">
                    <label for="facility_code">Facility Code</label>
                    <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{request()->input('facility_code')}}" readonly>
                  </div>
                @else
                <div class="form-group">
                    <label for="facility_list"><b class="text-danger">*</b>Override Facility</label>
                    <select class="form-control" name="facility_list" id="facility_list" required>
                        @foreach($facility_list as $f)
                        <option value="{{$f->id}}" {{(old('facility_list', auth()->user()->itr_facility_id) == $f->id) ? 'selected' : ''}}>{{$f->facility_name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @include('pidsr.inhouse_edcs.patient_defaults')
                @include('pidsr.inhouse_edcs.patient_defaults1')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_of_parentcaregiver" class="form-label">Name of Parent/Caregiver</label>
                            <input type="text" class="form-control" id="name_of_parentcaregiver" name="name_of_parentcaregiver" style="text-transform: uppercase;" value="{{old('name_of_parentcaregiver')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_contactno">Contact Number</label>
                            <input type="text" class="form-control" id="parent_contactno" name="parent_contactno" value="{{old('parent_contactno')}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                    </div>
                </div>
                @include('pidsr.inhouse_edcs.patient_defaults_investigator')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="fever" id="fever" value="Y">
                            Fever
                            </label>
                        </div>
                        <div class="d-none mt-3" id="fever_div">
                            <div class="form-group">
                                <label for="FeverOnset"><b class="text-danger">*</b>Fever Onset</label>
                                <input type="date" class="form-control" name="FeverOnset" id="FeverOnset" value="{{old('FeverOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="Rash" id="Rash" value="Y">
                            Rash
                            </label>
                        </div>
                        <div class="d-none mt-3" id="rash_div">
                            <div class="form-group">
                                <label for="RashOnset"><b class="text-danger">*</b>Rash Onset</label>
                                <input type="date" class="form-control" name="RashOnset" id="RashOnset" value="{{old('RashOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="Cough" id="Cough" value="Y">
                            Cough
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="KoplikSpot" id="KoplikSpot" value="Y">
                            Koplik Sign
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="RunnyNose" id="RunnyNose" value="Y">
                            Colds/Runny nose/Coryza
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="RedEyes" id="RedEyes" value="Y">
                            Red Eyes/Conjunctivitis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="ArthritisArthralgia" id="ArthritisArthralgia" value="Y">
                            Arthralgia/Arthritis
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                            Swollen lymphatic nodules
                            </label>
                        </div>
                        <div class="d-none mt-3" id="lymp_div">
                            <h6>Specify Location:</h6>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input lymp-location" name="LympNodLoc[]" id="LympNodLoc_1" value="CERVICAL">
                                    Cervical
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input lymp-location" name="LympNodLoc[]" id="LympNodLoc_2" value="POST-AURICULAR">
                                    Post-auricular
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input lymp-location" name="LympNodLoc[]" id="LympNodLoc_3" value="SUB-OCCIPITAL">
                                    Sub-occipital
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input lymp-location" name="LympNodLoc[]" id="LympNodLoc_4" value="OTHERS">
                                    Others
                                </label>
                            </div>
                            <div class="form-group mt-3 d-none" id="LympNodLocOthersDiv">
                                <label for="LympNodLocOthers" class="form-label">Specify Location</label>
                                <input type="text" class="form-control" id="LympNodLocOthers" name="LympNodLocOthers" style="text-transform: uppercase;" value="{{old('LympNodLocOthers')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="AreThereAny" id="AreThereAny" value="Y">
                                Are there any complications?
                            </label>
                        </div>
                        <div class="form-group mt-3 d-none" id="osymptoms_div">
                            <label for="Complications" class="form-label">Specify Complications</label>
                            <input type="text" class="form-control" id="Complications" name="Complications" style="text-transform: uppercase;" value="{{old('Complications')}}">
                        </div>
                        <div class="form-group">
                            <label for="OthSymptoms" class="form-label">Other Symptoms</label>
                            <input type="text" class="form-control" id="OthSymptoms" name="OthSymptoms" style="text-transform: uppercase;" value="{{old('OthSymptoms')}}">
                        </div>
                        <div class="form-group">
                            <label for="wfdiagnosis" class="form-label">Working/Final Diagnosis</label>
                            <input type="text" class="form-control" id="wfdiagnosis" name="wfdiagnosis" style="text-transform: uppercase;" value="{{old('wfdiagnosis')}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="MeasVacc"><span class="text-danger font-weight-bold">*</span>Patient received measles-containing vaccine (MCV)?</label>
                    <select class="form-control" name="MeasVacc" id="MeasVacc" required>
                        <option value="" disabled {{(is_null(old('MeasVacc'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('MeasVacc') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('MeasVacc') == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div id="MeasVacc_div" class="d-none">
                    <h6>Indicate the number of doses whiever is applicable:</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="MVDose">MV</label>
                              <input type="number" class="form-control" name="MVDose" id="MVDose" max="3">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="MRDose">MR</label>
                              <input type="number" class="form-control" name="MRDose" id="MRDose" max="3">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="MMRDose">MMR</label>
                              <input type="number" class="form-control" name="MMRDose" id="MMRDose" max="3">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="LastVacc">Date last dose received MCV</label>
                        <input type="date" class="form-control" name="LastVacc" id="LastVacc" value="{{old('LastVacc')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                    </div>
                    <div class="form-group">
                        <label for="VaccValidated"><span class="text-danger font-weight-bold">*</span>Measles vaccine received validated through:</label>
                        <select class="form-control" name="VaccValidated" id="VaccValidated">
                            <option value="" disabled {{(is_null(old('VaccValidated'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="VCARD" {{(old('MeasVacc') == 'Y') ? 'selected' : ''}}>Vaccination Card</option>
                            <option value="LOGSHEET" {{(old('MeasVacc') == 'Y') ? 'selected' : ''}}>Logsheet</option>
                            <option value="RECALL" {{(old('MeasVacc') == 'Y') ? 'selected' : ''}}>By recall</option>
                            <option value="OTHERS" {{(old('MeasVacc') == 'Y') ? 'selected' : ''}}>Others</option>
                        </select>
                    </div>
                    <div class="form-group mt-3 d-none" id="VaccValidatedOthers_div">
                        <label for="VaccValidatedOthers" class="form-label">Speficy other validation</label>
                        <input type="text" class="form-control" id="VaccValidatedOthers" name="VaccValidatedOthers" style="text-transform: uppercase;" value="{{old('VaccValidatedOthers')}}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="SpecialCampaigns"><span class="text-danger font-weight-bold">*</span>Was vaccination received during special campaigns?</label>
                        <select class="form-control" name="SpecialCampaigns" id="SpecialCampaigns">
                            <option value="" disabled {{(is_null(old('SpecialCampaigns'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('SpecialCampaigns') == 'Y') ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{(old('SpecialCampaigns') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div id="MeasVacc_no_div" class="d-none">
                    <h6>State the reason/s why patient did not receive any MCV</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_1" value="MOTHER WAS BUSY">
                        <label class="form-check-label" for="Reasons_1">Mother was busy</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_2" value="AGAINST BELIEF">
                        <label class="form-check-label" for="Reasons_2">Against belief</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_3" value="MEDICAL CONTRAINDICATION">
                        <label class="form-check-label" for="Reasons_3">Medical contraindication</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_4" value="FEAR OF SIDE EFFECTS">
                        <label class="form-check-label" for="Reasons_4">Fear of side effects</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_5" value="CHILD WAS SICK">
                        <label class="form-check-label" for="Reasons_5">Child was sick</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_6" value="NO VACCINE AVAILABLE">
                        <label class="form-check-label" for="Reasons_6">No vaccine available</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_7" value="VACCINATOR NOT AVAILABLE">
                        <label class="form-check-label" for="Reasons_7">Vaccinator not available</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_8" value="NOT ELIGIBLE FOR VACCINATION">
                        <label class="form-check-label" for="Reasons_8">Not eligible for vaccination</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_9" value="FORGOT SCHEDULE">
                        <label class="form-check-label" for="Reasons_9">Forgot schedule</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Reasons[]" id="Reasons_10" value="OTHERS">
                        <label class="form-check-label" for="Reasons_10">Other reasons</label>
                    </div>
                    <div class="form-group mt-3 d-none" id="ReasonsOthers_div">
                        <label for="OtherReasons" class="form-label">Specify other reasons</label>
                        <input type="text" class="form-control" id="OtherReasons" name="OtherReasons" style="text-transform: uppercase;" value="{{old('OtherReasons')}}">
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="VitaminA"><span class="text-danger font-weight-bold">*</span>Was the patient given Vitamin A during this illness?</label>
                    <select class="form-control" name="VitaminA" id="VitaminA" required>
                        <option value="" disabled {{(is_null(old('VitaminA'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('VitaminA') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('VitaminA') == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <hr>
                <div class="form-group mt-3">
                    <label for="Travel"><span class="text-danger font-weight-bold">*</span>With history of travel within 23 days prior to onset of rash?</label>
                    <select class="form-control" name="Travel" id="Travel" required>
                        <option value="" disabled {{(is_null(old('Travel'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('Travel') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('Travel') == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div id="travel_div" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PlaceTravelled" class="form-label">Place of Travel</label>
                                <input type="text" class="form-control" id="PlaceTravelled" name="PlaceTravelled" style="text-transform: uppercase;" value="{{old('PlaceTravelled')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TravelDate">Date of travel</label>
                                <input type="date" class="form-control" name="TravelDate" id="TravelDate" value="{{old('TravelDate')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="TravelOnset1" id="TravelOnset1" value="Y">
                        <label class="form-check-label" for="TravelOnset1"> < 7 days from rash onset</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="TravelOnset2" id="TravelOnset2" value="Y">
                        <label class="form-check-label" for="TravelOnset2">7-23 days from rash onset</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="ContactConfirmedCase"><span class="text-danger font-weight-bold">*</span>Was there a contact with a confirmed MEASLES case 7-23 days prior to rash onset?</label>
                    <select class="form-control" name="ContactConfirmedCase" id="ContactConfirmedCase" required>
                        <option value="" disabled {{(is_null(old('ContactConfirmedCase'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('ContactConfirmedCase') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('ContactConfirmedCase') == 'N') ? 'selected' : ''}}>No</option>
                        <option value="U" {{(old('ContactConfirmedCase') == 'U') ? 'selected' : ''}}>Unknown</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ContactConfirmedRubella"><span class="text-danger font-weight-bold">*</span>Was there a contact with a confirmed RUBELLA case 7-23 days prior to rash onset?</label>
                    <select class="form-control" name="ContactConfirmedRubella" id="ContactConfirmedRubella" required>
                        <option value="" disabled {{(is_null(old('ContactConfirmedRubella'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('ContactConfirmedRubella') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('ContactConfirmedRubella') == 'N') ? 'selected' : ''}}>No</option>
                        <option value="U" {{(old('ContactConfirmedRubella') == 'U') ? 'selected' : ''}}>Unknown</option>
                    </select>
                </div>
                <div class="d-none" id="contact_div">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ContactName" class="form-label">Name of Contact</label>
                                <input type="text" class="form-control" id="ContactName" name="ContactName" style="text-transform: uppercase;" value="{{old('ContactName')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ContactPlace" class="form-label">Place of Residence</label>
                                <input type="text" class="form-control" id="ContactPlace" name="ContactPlace" style="text-transform: uppercase;" value="{{old('ContactPlace')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ContactDate">Date of Contact</label>
                                <input type="date" class="form-control" name="ContactDate" id="ContactDate" value="{{old('ContactDate')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <h6>Tick the type of place where exposure probably occur</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_1" value="DAY CARE">
                    <label class="form-check-label" for="ProbExposure_1">Day care</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_2" value="BARANGAY">
                    <label class="form-check-label" for="ProbExposure_2">Barangay</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_3" value="HOME">
                    <label class="form-check-label" for="ProbExposure_3">Home</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_4" value="SCHOOL">
                    <label class="form-check-label" for="ProbExposure_4">School</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_5" value="HEALTH CARE FACILITY">
                    <label class="form-check-label" for="ProbExposure_5">Health Care Facility</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_6" value="DORMITORY">
                    <label class="form-check-label" for="ProbExposure_6">Dormitory</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="ProbExposure[]" id="ProbExposure_7" value="OTHERS">
                    <label class="form-check-label" for="ProbExposure_7">Others</label>
                </div>
                <div class="form-group mt-3 d-none" id="ProbExposure_7_div">
                    <label for="OtherExposure" class="form-label">Specify other place</label>
                    <input type="text" class="form-control" id="OtherExposure" name="OtherExposure" style="text-transform: uppercase;" value="{{old('OtherExposure')}}">
                </div>
                <div class="form-group mt-3">
                    <label for="OtherCase"><span class="text-danger font-weight-bold">*</span>Are there other known cases with fever and rash (regardless of presence of 3 C's) in the community?</label>
                    <select class="form-control" name="OtherCase" id="OtherCase" required>
                        <option value="" disabled {{(is_null(old('OtherCase'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('OtherCase') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('OtherCase') == 'N') ? 'selected' : ''}}>No</option>
                        <option value="U" {{(old('OtherCase') == 'U') ? 'selected' : ''}}>Unknown</option>
                    </select>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FinalClass"><span class="text-danger font-weight-bold">*</span>Final Classification</label>
                            <select class="form-control" name="FinalClass" id="FinalClass" required>
                                <option value="MEASLES COMPATIBLE" {{(old('FinalClass') == 'MEASLES COMPATIBLE') ? 'selected' : ''}}>Measles Compatible (Suspect)</option>
                                <option value="LABORATORY CONFIRMED MEASLES" {{(old('FinalClass') == 'LABORATORY CONFIRMED MEASLES') ? 'selected' : ''}}>Laboratory Confirmed Measles</option>
                                <option value="LABORATORY CONFIRMED RUBELLA" {{(old('FinalClass') == 'LABORATORY CONFIRMED RUBELLA') ? 'selected' : ''}}>Laboratory Confirmed Rubella</option>
                                <option value="EPI-LINKED CONFIRMED MEASLES" {{(old('FinalClass') == 'EPI-LINKED CONFIRMED MEASLES') ? 'selected' : ''}}>Epi-linked Confirmed Measles</option>
                                <option value="EPI-LINKED CONFIRMED RUBELLA" {{(old('FinalClass') == 'EPI-LINKED CONFIRMED RUBELLA') ? 'selected' : ''}}>Epi-linked Confirmed Rubella</option>
                                <option value="DISCARDED NON MEASLES/RUBELLA" {{(old('FinalClass') == 'DISCARDED NON MEASLES/RUBELLA') ? 'selected' : ''}}>Discarded Non Measles/Rubella (Negative)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="InfectionSource"><span class="text-danger font-weight-bold">*</span>Source of Infection</label>
                            <select class="form-control" name="InfectionSource" id="InfectionSource" required>
                                <option value="UNKNOWN" {{(old('InfectionSource') == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                                <option value="ENDEMIC" {{(old('InfectionSource') == 'ENDEMIC') ? 'selected' : ''}}>Endemic</option>
                                <option value="IMPORTED" {{(old('InfectionSource') == 'IMPORTED') ? 'selected' : ''}}>Imported</option>
                                <option value="IMPORT-RELATED" {{(old('InfectionSource') == 'IMPORT-RELATED') ? 'selected' : ''}}>Import-related</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                            <select class="form-control" name="Outcome" id="Outcome" required>
                                <option value="" disabled {{(is_null(old('Outcome'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="A" {{(old('Outcome') == 'A') ? 'selected' : ''}}>Alive</option>
                                <option value="HAMA" {{(old('Outcome') == 'HAMA') ? 'selected' : ''}}>Home Against Medical Advice</option>
                                <option value="D" {{(old('Outcome') == 'D') ? 'selected' : ''}}>Died</option>
                            </select>
                        </div>
                        <div id="died_div" class="d-none">
                            <div class="form-group">
                                <label for="Death">Date Died</label>
                                <input type="date" class="form-control" name="Death" id="Death" value="{{old('Death')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FinalDx" class="form-label">Final Diagnosis</label>
                            <input type="text" class="form-control" id="FinalDx" name="FinalDx" style="text-transform: uppercase;" value="{{old('FinalDx')}}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                  <label for="system_remarks">Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

@include('pidsr.inhouse_edcs.patient_defaults_js')

<script>
    $('form').on('submit', function(e) {
        if ($('.lymp-location:checked').length === 0 && $('#SwoLympNod').is(':checked')) {
            e.preventDefault();
            alert('Please check at least one Lymphatic Nodules Location.');
        }
    });
  
    $('#fever').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#fever_div').removeClass('d-none');
            $('#FeverOnset').prop('required', true);
        }
        else {
            $('#fever_div').addClass('d-none');
            $('#FeverOnset').prop('required', false);
        }
    });

    $('#Rash').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#rash_div').removeClass('d-none');
            $('#DONSET').prop('required', true);
        }
        else {
            $('#rash_div').addClass('d-none');
            $('#DONSET').prop('required', false);
        }
    });

    $('#SwoLympNod').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#lymp_div').removeClass('d-none');
        }
        else {
            $('#lymp_div').addClass('d-none');
        }
    });

    $('#LympNodLoc_4').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked') && $('#SwoLympNod').is(':checked')) {
            $('#LympNodLocOthersDiv').removeClass('d-none');
            $('#LympNodLocOthers').prop('required', true);
        }
        else {
            $('#LympNodLocOthersDiv').addClass('d-none');
            $('#LympNodLocOthers').prop('required', false);
        }
    }).trigger('change');

    $('#AreThereAny').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#osymptoms_div').removeClass('d-none');
            $('#Complications').prop('required', true);
        }
        else {
            $('#osymptoms_div').addClass('d-none');
            $('#Complications').prop('required', false);
        }
    });

    $('#MeasVacc').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#MeasVacc_div').removeClass('d-none');
            $('#MeasVacc_no_div').addClass('d-none');
            $('#SpecialCampaigns').prop('required', true);
        }
        else {
            $('#MeasVacc_div').addClass('d-none');
            $('#MeasVacc_no_div').removeClass('d-none');
            $('#SpecialCampaigns').prop('required', false);
        }
    }).trigger('change');

    $('#VaccValidated').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'OTHERS' && $('#MeasVacc').val() == 'Y') {
            $('#VaccValidatedOthers_div').removeClass('d-none');
            $('#VaccValidatedOthers').prop('required', true);
        }
        else {
            $('#VaccValidatedOthers_div').addClass('d-none');
            $('#VaccValidatedOthers').prop('required', false);
        }
    });

    $('#Reasons_10').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked') && $('#MeasVacc').val() == 'N') {
            $('#ReasonsOthers_div').removeClass('d-none');
            $('#OtherReasons').prop('required', true);
        }
        else {
            $('#ReasonsOthers_div').addClass('d-none');
            $('#OtherReasons').prop('required', false);
        }
    });

    $('#ProbExposure_7').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#ProbExposure_7_div').removeClass('d-none');
            $('#OtherExposure').prop('required', true);
        }
        else {
            $('#ProbExposure_7_div').addClass('d-none');
            $('#OtherExposure').prop('required', false);
        }
    });

    $('#Travel').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#travel_div').removeClass('d-none');
        }
        else {
            $('#travel_div').addClass('d-none');
        }
    }).trigger('change');

    $('#ContactConfirmedCase').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y' || $('#ContactConfirmedRubella').val() == 'Y') {
            $('#contact_div').removeClass('d-none');
        }
        else {
            $('#contact_div').addClass('d-none');
        }
    }).trigger('change');

    $('#ContactConfirmedRubella').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y' || $('#ContactConfirmedCase').val() == 'Y') {
            $('#contact_div').removeClass('d-none');
        }
        else {
            $('#contact_div').addClass('d-none');
        }
    }).trigger('change');

    $('#Outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'D') {
            $('#died_div').removeClass('d-none');
            $("#Death").prop('required', true);
        }
        else {
            $('#died_div').addClass('d-none');
            $("#Death").prop('required', false);
        }
    }).trigger('change');
</script>
@endsection