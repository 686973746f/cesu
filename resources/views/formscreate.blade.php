@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('covid_forms_create', $id)}}" method="POST">
            @csrf       
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>eCIF (version 9) - Create</b></div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appendix"><i class="fa fa-file mr-2" aria-hidden="true"></i>Appendix</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected while creating the CIF of the Patient:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgType')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <p>1.) The Case Investigation Form (CIF) is meant to be administered as an interview by a health care worker or any personnel of the DRU. <b>This is not a self-administered questionnaire.</b></p>
                        <p>2.) Please be advised that DRUs are only allowed to obtain <b>1 copy of accomplished CIF</b> from a patient.</p>
                        <p>3.) Please fill out all blanks and put a check mark on the appropriate box. <b>Items with asterisk mark <span class="text-danger">(*)</span> are required fields.</b></p>
                    </div>
                    <hr>
                    <label for=""><span class="text-danger font-weight-bold">*</span>Currently Creating CIF record for</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="#{{$records->id}} - {{$records->lname}}, {{$records->fname}} {{$records->mname}} | {{$records->getAge().'/'.substr($records->gender, 0, 1)}} | {{date('m/d/Y', strtotime($records->bdate))}}" disabled>
                        <div class="input-group-append">
                            <a class="btn btn-outline-primary" id="quickreclink" href="{{route('records.edit', ['record' => $records->id])}}">Edit Record</a>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="remarks">Remarks/Notes <small><i>(If Applicable)</i></small></label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks')}}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="morbidityMonth"><span class="text-danger font-weight-bold">*</span>Morbidity Month [MM] <i>(Kung kailan na-encode)</i></label>
                              <input type="date" class="form-control" id="morbidityMonth" name="morbidityMonth" min="{{date('Y-m-d')}}" max="{{(time() >= strtotime('16:00:00')) ? date('Y-m-d', strtotime('+1 Day')) : date('Y-m-d')}}" value="{{old('morbidityMonth', (time() >= strtotime('16:00:00')) ? date('Y-m-d', strtotime('+1 Day')) : date('Y-m-d'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for=""><span class="text-danger font-weight-bold">*</span>Morbidity Week (MW)</label>
                              <input type="text" class="form-control" value="{{!is_null(old('morbidityMonth')) ? date('W', strtotime(old('morbidityMonth'))) : date('W')}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="dateReported"><span class="text-danger font-weight-bold">*</span>Date Reported <i>(Kung kailan lumabas ang Swab Test Result)</i></label>
                      <input type="date" class="form-control" name="dateReported" id="dateReported" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{old('dateReported', date('Y-m-d'))}}" required>
                      <small class="text-muted">Note: For Positive/Negative Result, it will be automatically changed based on Date Released of Swab Result <i>(Under 2.7 Laboratory Information)</i>.</small>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="drunit"><span class="text-danger font-weight-bold">*</span>Disease Reporting Unit (DRU)</label>
                              <input type="text" class="form-control" name="drunit" id="drunit" value="{{old('drunit', 'CHO GENERAL TRIAS')}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drregion"><span class="text-danger font-weight-bold">*</span>DRU Region</label>
                                        <input type="text" class="form-control" name="drregion" id="drregion" value="{{old('drregion', '4A')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drprovince"><span class="text-danger font-weight-bold">*</span>DRU Province</label>
                                        <input type="text" class="form-control" name="drprovince" id="drprovince" value="{{old('drprovince', 'CAVITE')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><span class="text-danger font-weight-bold">*</span>Philhealth No.</label>
                                <input type="text" name="" id="" class="form-control" value="{{(is_null($records->philhealth)) ? 'N/A' : $records->philhealth}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if(!auth()->user()->isCesuAccount())
                            <div class="form-group">
                                <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                                <input type="text" name="interviewerName" id="interviewerName" class="form-control" value="{{(!is_null(auth()->user()->defaultInterviewer())) ? auth()->user()->defaultInterviewer() : auth()->user()->name}}" readonly required>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                                <select name="interviewerName" id="interviewerName" required>
                                    <option value="" disabled {{(empty(old('interviewerName'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach($interviewers as $key => $interviewer)
                                        <option value="{{$interviewer->lname.", ".$interviewer->fname}}" {{(old('interviewerName') == $interviewer->lname.", ".$interviewer->fname) ? 'selected' : ''}}>{{$interviewer->lname.", ".$interviewer->fname." ".$interviewer->mname}}{{(!is_null($interviewer->brgy_id)) ? " (".$interviewer->brgy->brgyName.")" : ''}}{{(!is_null($interviewer->desc)) ? " - ".$interviewer->desc : ""}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @php
                                if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                                    $intMobile = '09190664324';
                                }
                                else {
                                    $intMobile = '09190664324';
                                }
                            @endphp
                            <div class="form-group">
                                <label for="interviewerMobile"><span class="text-danger font-weight-bold">*</span>Contact Number of Interviewer</label>
                                <input type="number" name="interviewerMobile" id="interviewerMobile" class="form-control" value="{{old('interviewerMobile', $intMobile)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date of Interview</label>
                                <input type="date" name="interviewDate" id="interviewDate" class="form-control" value="{{old('interviewDate', date('Y-m-d'))}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantName">Name of Informant <small><i>(If patient unavailable)</i></small></label>
                                <input type="text" name="informantName" id="informantName" class="form-control" value="{{old('informantName')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantRelationship">Relationship</label>
                                <select class="form-control" name="informantRelationship" id="informantRelationship">
                                <option value="" disabled {{(is_null(old('informantRelationship'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Relative" {{(old('informantRelationship') == "Relative") ? 'selected' : ''}}>Family/Relative</option>
                                <option value="Friend" {{(old('informantRelationship') == "Friend") ? 'selected' : ''}}>Friend</option>
                                <option value="Others" {{(old('informantRelationship') == "Others") ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantMobile">Contact Number of Informant</label>
                                <input type="number" name="informantMobile" id="informantMobile" class="form-control" value="{{old('informantMobile')}}">
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <span class="text-danger font-weight-bold">*</span>If existing case (<i>check all that apply</i>)
                        </div>
                        <div class="card-body exCaseList">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("1", old('existingCaseList'))) ? 'checked' : 'checked'}}>
                                        <label class="form-check-label" for="">
                                            Not applicable (New case)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="2" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("2", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Not applicable (Unknown)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="3" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("3", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update symptoms
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="4" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("4", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update health status / outcome
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="5" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("5", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update case classification
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="6" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("6", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update vaccination
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="7" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("7", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update lab result
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="8" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("8", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update chest imaging findings
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="9" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("9", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update disposition
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="10" id="" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("10", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update exposure / travel history
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="11" id="ecothers" name="existingCaseList[]" required {{(is_array(old('existingCaseList')) && in_array("11", old('existingCaseList'))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Others
                                        </label>
                                    </div>
                                    <div id="divECOthers">
                                        <div class="form-group mt-2">
                                            <label for="ecOthersRemarks"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                            <input type="text" name="ecOthersRemarks" id="ecOthersRemarks" value="{{old('ecOthersRemarks')}}" class="form-control" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pType"><span class="text-danger font-weight-bold">*</span>Type of Client</label>
                                <select class="form-control" name="pType" id="pType" required>
                                <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>COVID-19 Case (Suspect, Probable, or Confirmed)</option>
                                <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>For RT-PCR Testing (Not a Case of Close Contact)</option>
                                </select>
                            </div>
                            <div id="ifCC">
                                <div class="form-group">
                                  <label for="ccType"><span class="text-danger font-weight-bold">*</span>Close Contact Type</label>
                                  <select class="form-control" name="ccType" id="ccType">
                                    <option value="1" {{(old('ccType') == 1) ? 'selected' : ''}}>Primary (1st Generation)</option>
                                    <option value="2" {{(old('ccType') == 2) ? 'selected' : ''}}>Secondary (2nd Generation)</option>
                                    <option value="3" {{(old('ccType') == 3) ? 'selected' : ''}}>Tertiary (3rd Generation)</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="isForHospitalization"><span class="text-danger font-weight-bold">*</span>For Hospitalization</label>
                              <select class="form-control" name="isForHospitalization" id="isForHospitalization" required>
                                <option value="1" {{(old('isForHospitalization') == 1) ? 'selected' : ''}}>Yes</option>
                                <option value="0" {{(old('isForHospitalization') == 0) ? 'selected' : 'selected'}}>No</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="testingCat"><span class="text-danger font-weight-bold">*</span>Testing Category/Subgroup</label>
                        <select class="form-control" name="testingCat" id="testingCat" required>
                        <option value="A4" {{(old('testingCat') == 'A4') ? 'selected' : ''}}>A4</option>
                        <option value="A1" {{(old('testingCat') == 'A1') ? 'selected' : ''}}>A1 - Frontline Workers in Health Facilities</option>
                        <option value="A2" {{(old('testingCat') == 'A2') ? 'selected' : ''}}>A2 - Senior Citizens/Persons Aged 60 and above</option>
                        <option value="A3" {{(old('testingCat') == 'A3') ? 'selected' : ''}}>A3 - Hospitalization/Pregnant/Operation/Comorbidities</option>
                        <!--
                        <option value="A" {{(collect(old('testingCat'))->contains("A")) ? 'selected' : ''}}>A. With Severe/Critical Symptoms</option>
                        <option value="B" {{(collect(old('testingCat'))->contains("B")) ? 'selected' : ''}}>B. With Mild Symptoms (Senior Citizens / Patients w. Comorbidity)</option>
                        <option value="C" {{(collect(old('testingCat'))->contains("C")) ? 'selected' : ''}}>C. With Mild Symptoms Only</option>
                        <optgroup label="Category D - No Symptoms but with Relevant History of Travel or Contact">
                            <option value="D.1" {{(collect(old('testingCat'))->contains("D.1")) ? 'selected' : ''}}>D.1 Contact Traced Individuals</option>
                            <option value="D.2" {{(collect(old('testingCat'))->contains("D.2")) ? 'selected' : ''}}>D.2 Health Care Workers</option>
                            <option value="D.3" {{(collect(old('testingCat'))->contains("D.3")) ? 'selected' : ''}}>D.3 Returning Overseas Filipino</option>
                            <option value="D.4" {{(collect(old('testingCat'))->contains("D.4")) ? 'selected' : ''}}>D.4 Locally Stranded Individuals (LSI)</option>
                        </optgroup>
                        <optgroup label="Category E - Frontliners Indirectly Involved in Healthcare Provision">
                            <optgroup label="E1 - High Direct Exposure to COVID-19 Regardless of Location">
                                <option value="E1.1" {{(collect(old('testingCat'))->contains("E1.1")) ? 'selected' : ''}}>E1.1 Quarantine Facilities</option>
                                <option value="E1.2" {{(collect(old('testingCat'))->contains("E1.2")) ? 'selected' : ''}}>E1.2 COVID-19 Swabbing Center</option>
                                <option value="E1.3" {{(collect(old('testingCat'))->contains("E1.3")) ? 'selected' : ''}}>E1.3 Contact Tracing</option>
                                <option value="E1.4" {{(collect(old('testingCat'))->contains("E1.4")) ? 'selected' : ''}}>E1.4 Personnel Conducting Swabbing</option>
                            </optgroup>
                            <optgroup label="E2 - Not High or Indirect Exposure to COVID-19">
                                <option value="E2.1" {{(collect(old('testingCat'))->contains("E2.1")) ? 'selected' : ''}}>E2.1 Quarantine Control Points (eg. AFP, BFP, etc.)</option>
                                <option value="E2.2" {{(collect(old('testingCat'))->contains("E2.2")) ? 'selected' : ''}}>E2.2 National/regional/local risk of reduction management</option>
                                <option value="E2.3" {{(collect(old('testingCat'))->contains("E2.3")) ? 'selected' : ''}}>E2.3 Government Employees</option>
                                <option value="E2.4" {{(collect(old('testingCat'))->contains("E2.4")) ? 'selected' : ''}}>E2.4 BHERTs</option>
                                <option value="E2.5" {{(collect(old('testingCat'))->contains("E2.5")) ? 'selected' : ''}}>E2.5 Bureau of Corrections & Bureau of Jail Penology and Management</option>
                                <option value="E2.6" {{(collect(old('testingCat'))->contains("E2.6")) ? 'selected' : ''}}>E2.6 One-Stop-Shop in the Management of the Returning Overseas Filipinos</option>
                                <option value="E2.7" {{(collect(old('testingCat'))->contains("E2.7")) ? 'selected' : ''}}>E2.7 Border Control or Patrol Officer (eg. Coast Guard)</option>
                                <option value="E2.8" {{(collect(old('testingCat'))->contains("E2.8")) ? 'selected' : ''}}>E2.8 Social Workers</option>
                            </optgroup>
                        </optgroup>
                        <optgroup label="Category F - Other vulnerable patients and those living in confined spaces">
                            <option value="F.1" {{(collect(old('testingCat'))->contains("F.1")) ? 'selected' : ''}}>F.1 Pregnant Patients</option>
                            <option value="F.2" {{(collect(old('testingCat'))->contains("F.2")) ? 'selected' : ''}}>F.2 Dialysis Patients</option>
                            <option value="F.3" {{(collect(old('testingCat'))->contains("F.3")) ? 'selected' : ''}}>F.3 Immunocompromised (HIV/AIDS)</option>
                            <option value="F.4" {{(collect(old('testingCat'))->contains("F.4")) ? 'selected' : ''}}>F.4 Chemo and radiotherapy patient</option>
                            <option value="F.5" {{(collect(old('testingCat'))->contains("F.5")) ? 'selected' : ''}}>F.5 Elective surgical procedures with high risk transmission</option>
                            <option value="F.6" {{(collect(old('testingCat'))->contains("F.6")) ? 'selected' : ''}}>F.6 Organ/Bone Marrow/Stem Cell Transplant</option>
                            <option value="F.7" {{(collect(old('testingCat'))->contains("F.7")) ? 'selected' : ''}}>F.7 Persons in Jail and Penitentiaries</option>
                        </optgroup>
                        <option value="G" {{(collect(old('testingCat'))->contains("G")) ? 'selected' : ''}}>G. Residents, occupants, or workes in a localized area with an active COVID-19 cluster</option>
                        <optgroup label="Category H - Frontliners in Tourist Zones">
                            <option value="H.1" {{(collect(old('testingCat'))->contains("H.1")) ? 'selected' : ''}}>H.1 Workers/Employees in the Hospitality and Tourism Sectors</option>
                            <option value="H.2" {{(collect(old('testingCat'))->contains("H.2")) ? 'selected' : ''}}>H.2 Travelers</option>
                        </optgroup>
                        <option value="I" {{(collect(old('testingCat'))->contains("I")) ? 'selected' : ''}}>I. Employees of Manufacturing Companies and Public Service Providers Registered in Economic Zones</option>
                        <optgroup label="Category J - Economy Workers">
                            <option value="J1.1" {{(collect(old('testingCat'))->contains('J1.1')) ? 'selected' : ''}}>J1.1 Transport and Logistics</option>
                            <option value="J1.2" {{(collect(old('testingCat'))->contains('J1.2')) ? 'selected' : ''}}>J1.2 Food Retails</option>
                            <option value="J1.3" {{(collect(old('testingCat'))->contains('J1.3')) ? 'selected' : ''}}>J1.3 Education</option>
                            <option value="J1.4" {{(collect(old('testingCat'))->contains('J1.4')) ? 'selected' : ''}}>J1.4 Financial Services</option>
                            <option value="J1.5" {{(collect(old('testingCat'))->contains('J1.5')) ? 'selected' : ''}}>J1.5 Non-food Retail</option>
                            <option value="J1.6" {{(collect(old('testingCat'))->contains('J1.6')) ? 'selected' : ''}}>J1.6 Services <small>(Hairdressers, manicurist, embalmers, security guards, messengers, massage therapists, etc.)</small></option>
                            <option value="J1.7" {{(collect(old('testingCat'))->contains('J1.7')) ? 'selected' : ''}}>J1.7 Market Vendors</option>
                            <option value="J1.8" {{(collect(old('testingCat'))->contains('J1.8')) ? 'selected' : ''}}>J1.8 Construction</option>
                            <option value="J1.9" {{(collect(old('testingCat'))->contains('J1.9')) ? 'selected' : ''}}>J1.9 Water Supply, Sewerage, Waste Management</option>
                            <option value="J1.10" {{(collect(old('testingCat'))->contains('J1.10')) ? 'selected' : ''}}>J1.10 Public Sector</option>
                            <option value="J1.11" {{(collect(old('testingCat'))->contains('J1.11')) ? 'selected' : ''}}>J1.11 Mass Media</option>
                            <option value="J.2" {{(collect(old('testingCat'))->contains('J.2')) ? 'selected' : ''}}>J.2 Other Employee not Covered in J.1 Category but required to undergo testing every quarter</option>
                        </optgroup>
                        -->
                        </select>
                        <small class="text-muted">Refer to Appendix 2 for more details (Button in top-right corner of this page)</small>
                    </div>
                    <div id="accordion">
                        <div class="card mb-3">
                            <div class="card-header" id="headingOne">
                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#patientInfo" aria-expanded="true" aria-controls="patientInfo">Part 1. Patient Information (Click to Show)</button>
                            </div>
                            <div id="patientInfo" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="card mb-3">
                                        <div class="card-header">1.1 Patient Profile</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Last Name</label>
                                                        <input type="text" class="form-control" value="{{$records->lname}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">First Name</label>
                                                        <input type="text" class="form-control" value="{{$records->fname}}" id="" disabled>
                                                    </div>
                                                </div> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Middle Name</label>
                                                        <input type="text" class="form-control" value="{{$records->mname}}" id="" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Birthdate (MM/DD/YYYY)</label>
                                                        <input type="text" class="form-control" value="{{date('m/d/Y', strtotime($records->bdate))}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Age</label>
                                                        <input type="text" class="form-control" value="{{$records->getAge($records->bdate)}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Gender</label>
                                                        <input type="text" class="form-control" value="{{$records->gender}}" id="" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Civil Status</label>
                                                        <input type="text" class="form-control" value="{{$records->cs}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Nationality</label>
                                                        <input type="text" class="form-control" value="{{$records->nationality}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Occupation</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation)) ? 'N/A' : $records->occupation}}" id="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Works in a Closed Setting</label>
                                                        <input type="text" class="form-control" value="{{$records->worksInClosedSetting}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header">1.2 Current Address in the Philippines and Contact Information</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">House No./Lot/Bldg.</label>
                                                        <input type="text" class="form-control" value="{{$records->address_houseno}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Street/Subdivision/Purok/Sitio</label>
                                                        <input type="text" class="form-control" value="{{$records->address_street}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Barangay</label>
                                                        <input type="text" class="form-control" value="{{$records->address_brgy}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Municipality/City</label>
                                                        <input type="text" class="form-control" value="{{$records->address_city}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Province</label>
                                                        <input type="text" class="form-control" value="{{$records->address_province}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Home Phone No. (& Area Code)</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->phoneno)) ? 'N/A' : $records->phoneno}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Cellphone No.</label>
                                                        <input type="text" class="form-control" value="{{$records->mobile}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Email Address</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->email)) ? 'N/A' : $records->email}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header">1.3 Permanent Address and Contact Information (If different from current address)</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">House No./Lot/Bldg.</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaaddress_houseno)) ? "N/A" : $records->permaaddress_houseno}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Street/Subdivision/Purok/Sitio</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaaddress_street)) ? "N/A" : $records->permaaddress_street}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Barangay</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaaddress_brgy)) ? "N/A" : $records->permaaddress_brgy}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Municipality/City</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaaddress_city)) ? "N/A" : $records->permaaddress_city}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Province</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaaddress_province)) ? "N/A" : $records->permaaddress_province}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Home Phone No. (& Area Code)</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaphoneno)) ? "N/A" : $records->permaphoneno}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Cellphone No.</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permamobile)) ? "N/A" : $records->permamobile}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Email Address</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->permaemail)) ? "N/A" : $records->permaemail}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header">1.4 Current Workplace Address and Contact Information</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Lot/Bldg.</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_lotbldg)) ? 'N/A' : $records->occupation_lotbldg}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Street/Zone</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_street)) ? 'N/A' : $records->occupation_street}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Barangay</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_brgy)) ? 'N/A' : $records->occupation_brgy}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Municipality/City</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_city)) ? 'N/A' : $records->occupation_city}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Province</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_province)) ? 'N/A' : $records->occupation_province}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Name of Workplace</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_name)) ? 'N/A' : $records->occupation_name}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Phone No./Cellphone No.</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_mobile)) ? 'N/A' : $records->occupation_mobile}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Email Address</label>
                                                        <input type="text" class="form-control" value="{{(is_null($records->occupation_email)) ? 'N/A' : $records->occupation_email}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header">2.5 COVID-19 Vaccination Information</div>
                                        <div class="card-body">
                                            @if(!is_null($records->vaccinationDate1))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Name of Vaccine</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{$records->vaccinationName1}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="">1.) First Dose Date</label>
                                                      <input type="date" class="form-control" name="" id="" value="{{$records->vaccinationDate1}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Vaccination Center/Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationFacility1) ? $records->vaccinationFacility1 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Region of Health Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationRegion1) ? $records->vaccinationRegion1 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Adverse Event/s</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->haveAdverseEvents1 == 1) ? 'YES' : 'NO'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!is_null($records->vaccinationDate2))
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="">2.) Second Dose Date</label>
                                                      <input type="date" class="form-control" name="" id="" value="{{$records->vaccinationDate2}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Vaccination Center/Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationFacility2) ? $records->vaccinationFacility2 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Region of Health Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationRegion2) ? $records->vaccinationRegion2 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Adverse Event/s</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->haveAdverseEvents2 == 1) ? 'YES' : 'NO'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(!is_null($records->vaccinationDate3))
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="">3.) Booster Dose Date</label>
                                                      <input type="date" class="form-control" name="" id="" value="{{$records->vaccinationDate3}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Vaccination Center/Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationFacility3) ? $records->vaccinationFacility3 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Region of Health Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationRegion3) ? $records->vaccinationRegion3 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Adverse Event/s</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->haveAdverseEvents3 == 1) ? 'YES' : 'NO'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(!is_null($records->vaccinationDate4))
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="">4.) 2ND Booster Dose Date</label>
                                                      <input type="date" class="form-control" name="" id="" value="{{$records->vaccinationDate4}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Vaccination Center/Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationFacility4) ? $records->vaccinationFacility4 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Region of Health Facility</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->vaccinationRegion4) ? $records->vaccinationRegion4 : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Adverse Event/s</label>
                                                        <input type="text" class="form-control" name="" id="" value="{{($records->haveAdverseEvents4 == 1) ? 'YES' : 'NO'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <p class="text-center">Not yet Vaccinated.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">1.5 Special Population</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isHealthCareWorker"><span class="text-danger font-weight-bold">*</span>Health Care Worker</label>
                                        <select class="form-control" name="isHealthCareWorker" id="isHealthCareWorker" required>
                                            <option value="0" {{(old('isHealthCareWorker', $set_ishcw) == 0 || is_null(old('isHealthCareWorker', $set_ishcw))) ? 'selected' : ''}}>No</option>
                                            <option value="1" {{(old('isHealthCareWorker', $set_ishcw) == 1) ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                    <div id="divisHealthCareWorker">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="healthCareCompanyName"><span class="text-danger font-weight-bold">*</span>Name of Health Facility</label>
                                                    <input type="text" class="form-control" name="healthCareCompanyName" id="healthCareCompanyName" value="{{old('healthCareCompanyName', $set_hcwname)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="healthCareCompanyLocation"><span class="text-danger font-weight-bold">*</span>Location</label>
                                                    <input type="text" class="form-control" name="healthCareCompanyLocation" id="healthCareCompanyLocation" value="{{old('healthCareCompanyLocation', $set_hcwlocation)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isOFW"><span class="text-danger font-weight-bold">*</span>Returning Overseas Filipino</label>
                                        <select class="form-control" name="isOFW" id="isOFW" required>
                                            <option value="1" {{(old('isOFW') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isOFW') == 0 || is_null(old('isOFW'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="divisOFW">
                                        <div class="form-group">
                                            <label for="OFWCountyOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                            <select class="form-control" name="OFWCountyOfOrigin" id="OFWCountyOfOrigin">
                                                <option value="" disabled {{(is_null(old('OFWCountyOfOrigin'))) ? 'selected' : ''}}>Choose...</option>
                                                @foreach ($countries as $country)
                                                    @if($country != 'Philippines')
                                                        <option value="{{$country}}" {{(old('OFWCountyOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                          <label for="OFWPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                          <input type="text" class="form-control" name="OFWPassportNo" id="OFWPassportNo" value="{{old('OFWPassportNo')}}" style="text-transform: uppercase;">
                                        </div>
                                        <div class="form-group">
                                          <label for="ofwType"><span class="text-danger font-weight-bold">*</span>OFW?</label>
                                          <select class="form-control" name="ofwType" id="ofwType">
                                            <option value="1" {{(old('ofwType') == "YES") ? 'selected' : ''}}>Yes</option>
                                            <option value="2" {{(old('ofwType') == "NO") ? 'selected' : ''}}>No (Non-OFW)</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="isFNT"><span class="text-danger font-weight-bold">*</span>Foreign National Traveler</label>
                                        <select class="form-control" name="isFNT" id="isFNT" required>
                                            <option value="1" {{(old('isFNT') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isFNT') == 0 || is_null(old('isFNT'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="divisFNT">
                                        <div class="form-group">
                                            <label for="FNTCountryOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                            <select class="form-control" name="FNTCountryOfOrigin" id="FNTCountryOfOrigin">
                                                <option value="" selected disabled>Choose...</option>
                                                @foreach ($countries as $country)
                                                    @if($country != 'Philippines')
                                                        <option value="{{$country}}" {{(old('FNTCountryOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="FNTPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                            <input type="text" class="form-control" name="FNTPassportNo" id="FNTPassportNo" value="{{old('FNTPassportNo')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="isLSI"><span class="text-danger font-weight-bold">*</span>Locally Stranded Individual/APOR/Traveler</label>
                                        <select class="form-control" name="isLSI" id="isLSI" required>
                                            <option value="1" {{(old('isLSI') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isLSI') == 0 || is_null(old('isLSI'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="divisLSI">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="LSIProvince"><span class="text-danger font-weight-bold">*</span>Province of Origin</label>
                                                  <select class="form-control" name="LSIProvince" id="LSIProvince">
                                                        <option value="" selected disabled>Choose...</option>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="LSICity"><span class="text-danger font-weight-bold">*</span>City of Origin</label>
                                                    <select class="form-control" name="LSICity" id="LSICity">
                                                          <option value="" selected disabled>Choose...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                          <label for="lsiType"><span class="text-danger font-weight-bold">*</span>Type</label>
                                          <select class="form-control" name="lsiType" id="lsiType">
                                            <option value="1" {{(old('lsiType') == 1) ? 'selected' : ''}}>Locally Stranted Individual</option>
                                            <option value="0" {{(old('lsiType') == 2) ? 'selected' : ''}}>Authorized Person Outside Residence/Local Traveler</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="isLivesOnClosedSettings"><span class="text-danger font-weight-bold">*</span>Lives in Closed Settings</label>
                                        <select class="form-control" name="isLivesOnClosedSettings" id="isLivesOnClosedSettings" required>
                                            <option value="1" {{(old('isLivesOnClosedSettings') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isLivesOnClosedSettings') == 0 || is_null(old('isLivesOnClosedSettings'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="divisLivesOnClosedSettings">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="institutionType"><span class="text-danger font-weight-bold">*</span>Specify Institution Type</label>
                                                  <input type="text" class="form-control" name="institutionType" id="institutionType" value="{{old('institutionType')}}" style="text-transform: uppercase;">
                                                  <small><i>(e.g. prisons, residential facilities, retirement communities, care homes, camps etc.)</i></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="institutionName"><span class="text-danger font-weight-bold">*</span>Name of Institution</label>
                                                    <input type="text" class="form-control" name="institutionName" id="institutionName" value="{{old('institutionName')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 2. Case Investigation Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header">2.1 Consultation Information</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="havePreviousCovidConsultation"><span class="text-danger font-weight-bold">*</span>Have previous COVID-19 related consultation?</label>
                                                <select class="form-control" name="havePreviousCovidConsultation" id="havePreviousCovidConsultation" required>
                                                    <option value="" selected disabled>Choose...</option>
                                                    <option value="1" {{(old('havePreviousCovidConsultation') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('havePreviousCovidConsultation') == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divYes1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="facilityNameOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Name of facility where first consult was done</label>
                                                            <input type="text" class="form-control" name="facilityNameOfFirstConsult" id="facilityNameOfFirstConsult" value="{{old('facilityNameOfFirstConsult')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dateOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Date of First Consult</label>
                                                            <input type="date" class="form-control" name="dateOfFirstConsult" id="dateOfFirstConsult" value="{{old('dateOfFirstConsult')}}" max="{{date('Y-m-d')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header">2.2 Disposition at Time of Report / Quarantine Status</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="dispositionType"><span class="text-danger font-weight-bold">*</span>Status</label>
                                                <select class="form-control" name="dispositionType" id="dispositionType">
                                                    <option value="1" {{(old('dispositionType') == 1) ? 'selected' : ''}} id="disopt1">Admitted in hospital</option>
                                                    <option value="6" {{(old('dispositionType') == 6) ? 'selected' : ''}} id="disopt6">Admitted in General Trias Isolation Facility</option>
                                                    <option value="7" {{(old('dispositionType') == 7) ? 'selected' : ''}} id="disopt7">Admitted in General Trias Isolation Facility #2 (Eagle Ridge Brgy. Javalera)</option>
                                                    <option value="2" {{(old('dispositionType') == 2) ? 'selected' : ''}} id="disopt2">Admitted in OTHER isolation/quarantine facility</option>
                                                    <option value="3" {{(old('dispositionType') == 3 || is_null(old('dispositionType'))) ? 'selected' : ''}} id="disopt3">In home isolation/quarantine</option>
                                                    <option value="4" {{(old('dispositionType') == 4) ? 'selected' : ''}} id="disopt4">Discharged to home</option>
                                                    <option value="5" {{(old('dispositionType') == 5) ? 'selected' : ''}} id="disopt5">Others</option>
                                                </select>
                                            </div>
                                            <div id="divYes5">
                                                <div class="form-group">
                                                    <label for="dispositionName" id="dispositionlabel"></label>
                                                    <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="divYes6">
                                                <div class="form-group">
                                                    <label for="dispositionDate" id="dispositiondatelabel"></label>
                                                    <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate', date('Y-m-d\TH:i'))}}" max="{{date('Y-m-d').'T23:59'}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header"><span class="text-danger font-weight-bold">*</span>2.3 Health Status at Consult</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <select class="form-control" name="healthStatus" id="healthStatus" required>
                                                    <option value="Asymptomatic" {{(old('healthStatus') == 'Asymptomatic') ? 'selected' : ''}}>Asymptomatic </option>
                                                    <option value="Mild" {{(old('healthStatus') == 'Mild') ? 'selected' : ''}}>Mild</option>
                                                    <option value="Moderate" {{(old('healthStatus') == 'Moderate') ? 'selected' : ''}}>Moderate</option>
                                                    <option value="Severe" {{(old('healthStatus') == 'Severe') ? 'selected' : ''}}>Severe</option>
                                                    <option value="Critical" {{(old('healthStatus') == 'Critical') ? 'selected' : ''}}>Critical</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header"><span class="text-danger font-weight-bold">*</span>2.4 Case Classification</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <select class="form-control" name="caseClassification" id="caseClassification" required>
                                                    <option value="Probable" {{(old('caseClassification') == 'Probable') ? 'selected' : ''}}>Probable</option>
                                                    <option value="Suspect" {{(old('caseClassification') == 'Suspect') ? 'selected' : 'selected'}}>Suspect</option>
                                                    <option value="Confirmed" {{(old('caseClassification') == 'Confirmed') ? 'selected' : ''}}>Confirmed (POSITIVE +)</option>
                                                    <option value="Non-COVID-19 Case" {{(old('caseClassification') == 'Non-COVID-19 Case') ? 'selected' : ''}}>Non-COVID-19 Case (NEGATIVE -)</option>
                                                </select>
                                            </div>
                                            @if($is_cutoff && $records->id == $records->getNewCif() && $records->caseClassification != 'Confirmed')
                                                <div id="cutoffwarning" class="d-none">
                                                    <div class="alert alert-warning" role="alert">
                                                        <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i>Warning: Encoding Confirmed Patients for today is over.
                                                        <hr>
                                                        You can pre-encode the data by changing the Date of Morbidity Month to the Tomorrow's Date (which is {{date('m/d/Y', strtotime('+1 Day'))}})
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="alert alert-info mt-3" role="alert">
                                                <p><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Note:</p>
                                                <p>IF <strong>Suspected</strong> or <strong>Probable</strong> = Will <strong>APPEAR</strong> on For Swab List</p>
                                                <p>IF <strong class="text-danger">Confirmed</strong> or <strong class="text-success">Non-COVID-19 Case</strong> = Will <strong>NOT APPEAR</strong> on For Swab List</p>
                                            </div>
                                            <div id="confirmedVariant">
                                                <div class="form-group">
                                                    <label for="confirmedVariantName"><span class="text-danger font-weight-bold">*</span>COVID-19 Variant</label>
                                                    <select class="form-control" name="confirmedVariantName" id="confirmedVariantName">
                                                        <option value="" {{(is_null(old('confirmedVariantName'))) ? 'selected' : ''}}>Unspecified</option>
                                                        <option value="ALPHA" {{(old('confirmedVariantName') == 'ALPHA') ? 'selected' : ''}}>ALPHA (B.1.1.7) - GB</option>
                                                        <option value="BETA" {{(old('confirmedVariantName') == 'BETA') ? 'selected' : ''}}>BETA (B.1.351) - ZA</option>
                                                        <option value="DELTA" {{(old('confirmedVariantName') == 'DELTA') ? 'selected' : ''}}>DELTA (B.1.617.2) - IN</option>
                                                        <option value="GAMMA" {{(old('confirmedVariantName') == 'GAMMA') ? 'selected' : ''}}>GAMMA (P.1) - BR</option>
                                                        <option value="OMICRON" {{(old('confirmedVariantName') == 'OMICRON') ? 'selected' : ''}}>OMICRON (B.1.1.529)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="askIfReinfected">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="reinfected" id="reinfected" value="1" {{(old('reinfected') == 1) ? 'checked' : ''}}>
                                                      Case of Re-infection
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-header">2.6 Clinical Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="dateOnsetOfIllness"><span class="text-danger font-weight-bold d-none" id="onsetasterisk">*</span>Date of Onset of Illness</label>
                                              <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}" value="{{old('dateOnsetOfIllness')}}">
                                            </div>
                                            <div class="card">
                                                <div class="card-header">Signs and Symptoms (Check all that apply)</div>
                                                <div class="card-body">
                                                    <div class="row symptomsList">
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Fever"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck2"
                                                                  {{(is_array(old('sasCheck')) && in_array("Fever", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck2">Fever</label>
                                                            </div>
                                                            <div id="divFeverChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
                                                                  <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" max="90" step=".1" value="{{old('SASFeverDeg', '38')}}">
                                                                </div>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Cough"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck3"
                                                                  {{(is_array(old('sasCheck')) && in_array("Cough", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck3">Cough</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Coryza"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck9"
                                                                  {{(is_array(old('sasCheck')) && in_array("Coryza", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck9">Colds/Coryza</label>
                                                            </div>
                                                            <!--
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Colds"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck19"
                                                                  {{(is_array(old('sasCheck')) && in_array("Colds", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck19">Colds</label>
                                                            </div>
                                                            -->
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="General Weakness"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck4"
                                                                  {{(is_array(old('sasCheck')) && in_array("General Weakness", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck4">General Weakness</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Fatigue"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck5"
                                                                  {{(is_array(old('sasCheck')) && in_array("Fatigue", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck5">Fatigue</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Headache"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck6"
                                                                  {{(is_array(old('sasCheck')) && in_array("Headache", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck6">Headache</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Myalgia"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck7"
                                                                  {{(is_array(old('sasCheck')) && in_array("Myalgia", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck7">Body Pain/Myalgia</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Sore throat"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck8"
                                                                  {{(is_array(old('sasCheck')) && in_array("Sore throat", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck8">Sore Throat</label>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Dyspnea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck10"
                                                                  {{(is_array(old('sasCheck')) && in_array("Dyspnea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck10">Dyspnea/Shortness of Breath</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Anorexia"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck11"
                                                                  {{(is_array(old('sasCheck')) && in_array("Anorexia", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck11">Anorexia/Eating Disorder</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Nausea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck12"
                                                                  {{(is_array(old('sasCheck')) && in_array("Nausea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck12">Nausea</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Vomiting"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck13"
                                                                  {{(is_array(old('sasCheck')) && in_array("Vomiting", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck13">Vomiting</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Diarrhea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck14"
                                                                  {{(is_array(old('sasCheck')) && in_array("Diarrhea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck14">Diarrhea</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Altered Mental Status"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck15"
                                                                  {{(is_array(old('sasCheck')) && in_array("Altered Mental Status", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck15">Altered Mental Status</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Anosmia (Loss of Smell)"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck16"
                                                                  {{(is_array(old('sasCheck')) && in_array("Anosmia (Loss of Smell)", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck16">Loss of Smell (Anosmia)</small></label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Ageusia (Loss of Taste)"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck17"
                                                                  {{(is_array(old('sasCheck')) && in_array("Ageusia (Loss of Taste)", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck17">Loss of Taste (Ageusia)</small></label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Others"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck18"
                                                                  {{(is_array(old('sasCheck')) && in_array("Others", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck18">Others</label>
                                                            </div>
                                                            <div id="divSASOtherChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings <small>(Separate each with commas [,])</small></label>
                                                                  <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks')}}" style="text-transform: uppercase;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">Comorbidities / Reason for Hospitalization <small><i>(Check all that apply if present)</i></small></div>
                                                <div class="card-body">
                                                    <div class="row comoOpt">
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="None"
                                                                  name="comCheck[]"
                                                                  id="comCheck1"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("None", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck1">None</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Hypertension"
                                                                  name="comCheck[]"
                                                                  id="comCheck2"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Hypertension", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck2">Hypertension</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Diabetes"
                                                                  name="comCheck[]"
                                                                  id="comCheck3"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Diabetes", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck3">Diabetes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Heart Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck4"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Heart Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck4">Heart Disease</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Lung Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck5"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Lung Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck5">Lung Disease</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Gastrointestinal"
                                                                  name="comCheck[]"
                                                                  id="comCheck6"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Gastrointestinal", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck6">Gastrointestinal</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Genito-urinary"
                                                                  name="comCheck[]"
                                                                  id="comCheck7"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Genito-urinary", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck7">Genito-urinary</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Neurological Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck8"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Neurological Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck8">Neurological Disease</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Cancer"
                                                                  name="comCheck[]"
                                                                  id="comCheck9"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Cancer", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck9">Cancer (for Chemotheraphy/Radiotheraphy)</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Dialysis"
                                                                  name="comCheck[]"
                                                                  id="comCheck11"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Dialysis", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck11">For Dialysis</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Operation"
                                                                  name="comCheck[]"
                                                                  id="comCheck12"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Operation", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck12">For Operation</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Transplant"
                                                                  name="comCheck[]"
                                                                  id="comCheck13"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Transplant", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck13">Had Organ Transplant/Bone Marrow/Stem Cell Transplant (for the Past 6 Months)</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Others"
                                                                  name="comCheck[]"
                                                                  id="comCheck10"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Others", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck10">Others</label>
                                                            </div>
                                                            <div id="divComOthersChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="COMOOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                                  <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks')}}" style="text-transform: uppercase;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><span class="text-danger font-weight-bold">*</span>Pregnant?</label>
                                                        <input type="text" class="form-control" value="{{($records->isPregnant == 1) ? "Yes" : "No"}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="PregnantLMP"><span class="text-danger font-weight-bold">*</span>Last Menstrual Period (LMP)</label>
                                                        <input type="date" class="form-control" name="PregnantLMP" id="PregnantLMP" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('PregnantLMP')}}" {{($records->gender == "FEMALE" && $records->isPregnant == 1) ? 'required' : 'disabled'}}>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <label for="highRiskPregnancy"><span class="text-danger font-weight-bold">*</span>High Risk Pregnancy?</label>
                                              <select class="form-control" name="highRiskPregnancy" id="highRiskPregnancy" {{($records->gender == "FEMALE" && $records->isPregnant == 1) ? 'required' : 'disabled'}}>
                                                <option value="0" {{(old('highRiskPregnancy') == 0) ? 'selected' : ''}}>No</option>
                                                <option value="1" {{(is_null(old('highRiskPregnancy')) || old('highRiskPregnancy') == 1) ? 'selected' : ''}}>Yes</option>
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                      <label for="diagWithSARI"><span class="text-danger font-weight-bold">*</span>Was diagnosed to have Severe Acute Respiratory Illness?</label>
                                      <select class="form-control" name="diagWithSARI" id="diagWithSARI" required>
                                        <option value="1" {{(old('diagWithSARI') == 1) ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{(is_null(old('diagWithSARI')) || old('diagWithSARI') == 0) ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            Chest imaging findings suggestive of COVID-19
                                            <hr>
                                            <span class="text-danger font-weight-bold">*</span>Imaging Done
                                        </div>
                                        <div class="card-body imaOptions">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                      <label for="">Date done</label>
                                                      <input type="date" class="form-control" name="imagingDoneDate" id="imagingDoneDate" value="{{old('imagingDoneDate')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                      <label for="imagingDone">Imaging done</label>
                                                      <select class="form-control" name="imagingDone" id="imagingDone" required>
                                                        <option value="None" {{(old('imagingDone') == "None") ? 'selected' : ''}}>None</option>
                                                        <option value="Chest Radiography" {{(old('imagingDone') == "Chest Radiography") ? 'selected' : ''}}>Chest Radiography</option>
                                                        <option value="Chest CT" {{(old('imagingDone') == "Chest CT") ? 'selected' : ''}}>Chest CT</option>
                                                        <option value="Lung Ultrasound" {{(old('imagingDone') == "Lung Ultrasound") ? 'selected' : ''}}>Lung Ultrasound</option>
                                                      </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                      <label for="imagingResult">Results</label>
                                                      <select class="form-control" name="imagingResult" id="imagingResult">
                                                      </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                </div>
                                                <div class="col-md-4">
                                                    <div id="divImagingOthers">
                                                        <div class="form-group">
                                                          <label for="imagingOtherFindings"><span class="text-danger font-weight-bold">*</span>Specify findings</label>
                                                          <input type="text" class="form-control" name="imagingOtherFindings" id="imagingOtherFindings" value="{{old('imagingOtherFindings')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">2.7 Laboratory Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="testedPositiveUsingRTPCRBefore"><span class="text-danger font-weight-bold">*</span>Have you ever tested positive using RT-PCR before?</label>
                                                <select class="form-control" name="testedPositiveUsingRTPCRBefore" id="testedPositiveUsingRTPCRBefore" required>
                                                  <option value="1" {{(old('testedPositiveUsingRTPCRBefore') == 1) ? 'selected' : ''}}>Yes</option>
                                                  <option value="0" {{(is_null(old('testedPositiveUsingRTPCRBefore')) || old('testedPositiveUsingRTPCRBefore') == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="testedPositiveNumOfSwab"><span class="text-danger font-weight-bold">*</span>Number of previous RT-PCR swabs done</label>
                                                <input type="number" class="form-control" name="testedPositiveNumOfSwab" id="testedPositiveNumOfSwab" min="0" value="{{(is_null(old('testedPositiveNumOfSwab'))) ? '0' : old('testedPositiveNumOfSwab')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divIfTestedPositiveUsingRTPCR">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="testedPositiveSpecCollectedDate"><span class="text-danger font-weight-bold">*</span>Date of Specimen Collection</label>
                                                    <input type="date" class="form-control" name="testedPositiveSpecCollectedDate" id="testedPositiveSpecCollectedDate" max="{{date('Y-m-d')}}" value="{{old('testedPositiveSpecCollectedDate')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="testedPositiveLab">Laboratory</label>
                                                  <input type="text" class="form-control" name="testedPositiveLab" id="testedPositiveLab" value="{{old('testedPositiveLab')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testType1"><span class="text-danger font-weight-bold">*</span>#1 - Type of test</label>
                                                <select class="form-control" name="testType1" id="testType1">
                                                    @if(auth()->user()->isCesuAccount())
                                                    <option value="" {{(is_null(old('testType1'))) ? 'selected' : ''}}>N/A</option>
                                                    @endif
                                                    <option value="OPS" {{(old('testType1') == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                                    <option value="NPS" {{(old('testType1') == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                                    <option value="OPS AND NPS" {{(old('testType1') == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                                    <option value="ANTIGEN" {{(old('testType1') == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                                    <option value="ANTIBODY" {{(old('testType1') == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                                    <option value="CARTRIDGE" {{(old('testType1') == 'CARTRIDGE') ? 'selected' : ''}}>Cartridge</option>
                                                    <option value="OTHERS" {{(old('testType1') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                </select>
                                            </div>
                                            <div id="divTypeOthers1" class="d-none">
                                                <div class="form-group">
                                                    <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Reason</label>
                                                    <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="ifAntigen1" class="d-none">
                                                <div class="form-group">
                                                    <label for="antigen_id1">Antigen Kit</label>
                                                    <select class="form-control" name="antigen_id1" id="antigen_id1">
                                                        <option value="" disabled {{(is_null(old('antigen_id1', $records->antigen_id1))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach($antigen_list as $ai)
                                                        <option value="{{$ai->id}}" {{(old('antigen_id1') == $ai->id) ? 'selected' : ''}}>{{$ai->antigenKitShortName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="antigenLotNo1">Antigen Lot No <small>(Leave Blank to use Default)</small></label>
                                                    <input type="text" class="form-control" name="antigenLotNo1" id="antigenLotNo1" value="{{old('antigenLotNo1')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date Collected</label>
                                              <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{$mindate}}" max="{{$enddate}}" value="{{old('testDateCollected1')}}">
                                              <small class="text-muted">Note: This also considered the first day of Quarantine Period.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for=""><span class="text-danger font-weight-bold d-none" id="reqtc1">*</span>Time Collected</label>
                                                <input type="time" name="oniTimeCollected1" id="oniTimeCollected1" class="form-control" value="{{old('oniTimeCollected1')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testLaboratory1"><span class="text-danger font-weight-bold d-none" id="reql1">*</span>Laboratory</label>
                                                <input type="text" class="form-control" name="testLaboratory1" id="testLaboratory1" value="{{old('testLaboratory1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testResult1"><span class="text-danger font-weight-bold">*</span>Results</label>
                                                <select class="form-control" name="testResult1" id="testResult1" required>
                                                  <option value="PENDING" id="tro1_pending" {{(old('testResult1') == 'PENDING') ? 'selected' : ''}}>Pending</option>
                                                  <option value="POSITIVE" id="tro1_positive" {{(old('testResult1') == 'POSITIVE') ? 'selected' : ''}}>Positive (will change the Case Classification to 'Confirmed')</option>
                                                  <option value="NEGATIVE" id="tro1_negative" {{(old('testResult1') == 'NEGATIVE') ? 'selected' : ''}}>Negative (will change the Case Classification to 'Non-COVID Case')</option>
                                                  <option value="EQUIVOCAL" id="tro1_equivocal" {{(old('testResult1') == 'EQUIVOCAL') ? 'selected' : ''}}>Equivocal</option>
                                                  <option value="OTHERS" id="tro1_others" {{(old('testResult1') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                </select>
                                              </div>
                                              <div id="divResultOthers1">
                                                  <div class="form-group">
                                                      <label for="testResultOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                                      <input type="text" class="form-control" name="testResultOtherRemarks1" id="testResultOtherRemarks1" value="{{old('testResultOtherRemarks1')}}" style="text-transform: uppercase;">
                                                  </div>
                                              </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="ifDateReleased1">
                                                <div class="form-group">
                                                    <label for="testDateReleased1"><span class="text-danger font-weight-bold">*</span>Date released</label>
                                                    <input type="date" class="form-control" name="testDateReleased1" id="testDateReleased1" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('testDateReleased1')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testType2"><span class="text-danger font-weight-bold">*</span>#2 - Type of test</label>
                                              <select class="form-control" name="testType2" id="testType2">
                                                    <option value="">N/A</option>
                                                    <option value="OPS" {{(old('testType2') == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                                    <option value="NPS" {{(old('testType2') == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                                    <option value="OPS AND NPS" {{(old('testType2') == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                                    <option value="ANTIGEN" {{(old('testType2') == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                                    <option value="ANTIBODY" {{(old('testType2') == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                                    <option value="CARTRIDGE" {{(old('testType2') == 'CARTRIDGE') ? 'selected' : ''}}>Cartridge</option>
                                                    <option value="OTHERS" {{(old('testType2') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                              </select>
                                            </div>
                                            <div id="divTypeOthers2" class="d-none">
                                                <div class="form-group">
                                                  <label for="testTypeOtherRemarks2"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                                  <input type="text" class="form-control" name="testTypeOtherRemarks2" id="testTypeOtherRemarks2" value="{{old('testTypeOtherRemarks2')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="ifAntigen2" class="d-none">
                                                <div class="form-group">
                                                    <label for="antigen_id2">Antigen Kit</label>
                                                    <select class="form-control" name="antigen_id2" id="antigen_id2">
                                                        <option value="" disabled {{(is_null(old('antigen_id2', $records->antigen_id2))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach($antigen_list as $ai)
                                                        <option value="{{$ai->id}}" {{(old('antigen_id2') == $ai->id) ? 'selected' : ''}}>{{$ai->antigenKitShortName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="antigenLotNo2">Antigen Lot No <small>(Leave Blank to use Default)</small></label>
                                                    <input type="text" class="form-control" name="antigenLotNo2" id="antigenLotNo2" value="{{old('antigenLotNo2')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testDateCollected2"><span class="text-danger font-weight-bold">*</span>Date Collected</label>
                                                <input type="date" class="form-control" name="testDateCollected2" id="testDateCollected2" min="{{$mindate}}" max="{{$enddate}}" value="{{old('testDateCollected2')}}">
                                                <small class="text-muted">Note: This also considered the first day of Quarantine Period.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="oniTimeCollected2"><span class="text-danger font-weight-bold d-none" id="reql2">*</span>Time Collected</label>
                                                <input type="time" name="oniTimeCollected2" id="oniTimeCollected2" class="form-control" value="{{old('oniTimeCollected2')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testLaboratory2"><span class="text-danger font-weight-bold d-none" id="reqtc2">*</span>Laboratory</label>
                                                <input type="text" class="form-control" name="testLaboratory2" id="testLaboratory2" value="{{old('testLaboratory2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testResult2"><span class="text-danger font-weight-bold">*</span>Results</label>
                                              <select class="form-control" name="testResult2" id="testResult2">
                                                <option value="PENDING" id="tro2_pending" {{(old('testResult2') == 'PENDING') ? 'selected' : ''}}>Pending</option>
                                                <option value="POSITIVE" id="tro2_positive" {{(old('testResult2') == 'POSITIVE') ? 'selected' : ''}}>Positive (will change the Case Classification to 'Confirmed')</option>
                                                <option value="NEGATIVE" id="tro2_negative" {{(old('testResult2') == 'NEGATIVE') ? 'selected' : ''}}>Negative (will change the Case Classification to 'Non-COVID Case')</option>
                                                <option value="EQUIVOCAL" id="tro2_equivocal" {{(old('testResult2') == 'EQUIVOCAL') ? 'selected' : ''}}>Equivocal</option>
                                                <option value="OTHERS" id="tro2_others" {{(old('testResult2') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                              </select>
                                            </div>
                                            <div id="divResultOthers2" class="d-none">
                                                <div class="form-group">
                                                    <label for="testResultOtherRemarks2"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                                    <input type="text" class="form-control" name="testResultOtherRemarks2" id="testResultOtherRemarks2" value="{{old('testResultOtherRemarks2')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="ifDateReleased2" class="d-none">
                                                <div class="form-group">
                                                    <label for="testDateReleased2">Date released</label>
                                                    <input type="date" class="form-control" name="testDateReleased2" id="testDateReleased2" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('testDateReleased2')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">2.8 Outcome/Condition at Time of Report</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="outcomeCondition"><span class="text-danger font-weight-bold">*</span>Select Outcome/Condition</label>
                                      <select class="form-control" name="outcomeCondition" id="outcomeCondition" required>
                                        <option value="Active" {{(old('outcomeCondition') == 'Active') ? 'selected' : ''}}>Active (Currently admitted or in isolation/quarantine)</option>
                                        <option value="Recovered" {{(old('outcomeCondition') == 'Recovered') ? 'selected' : ''}}>Recovered</option>
                                        <option value="Died" {{(old('outcomeCondition') == 'Died') ? 'selected' : ''}}>Died</option>
                                      </select>
                                      <small class="text-danger d-none" id="outcomeWarningText">Note: When selecting the Outcome to Recovered or Died, the [2.4 Case Classification] of the patient will be automatically set to "Confirmed Case" and this CIF will be LOCKED for editing.</small>
                                    </div>
                                    <div id="ifOutcomeRecovered">
                                        <div class="form-group">
                                          <label for="outcomeRecovDate"><span class="text-danger font-weight-bold">*</span>Date of Recovery</label>
                                          <input type="date" class="form-control" name="outcomeRecovDate" id="outcomeRecovDate" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{old('outcomeRecovDate')}}">
                                        </div>
                                    </div>
                                    <div id="ifOutcomeDied">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="outcomeDeathDate"><span class="text-danger font-weight-bold">*</span>Date of Death</label>
                                                    <input type="date" class="form-control" name="outcomeDeathDate" id="outcomeDeathDate" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{old('outcomeDeathDate')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="deathImmeCause"><span class="text-danger font-weight-bold">*</span>Immediate Cause</label>
                                                    <input type="text" class="form-control" name="deathImmeCause" id="deathImmeCause" value="{{old('deathImmeCause')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathAnteCause">Antecedent Cause</label>
                                                    <input type="text" class="form-control" name="deathAnteCause" id="deathAnteCause" value="{{old('deathAnteCause')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Underlying Cause</label>
                                                    <input type="text" class="form-control" name="deathUndeCause" id="deathUndeCause" value="{{old('deathUndeCause')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Contributory Conditions</label>
                                                    <input type="text" class="form-control" name="contriCondi" id="contriCondi" value="{{old('contriCondi')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 3. Contact Tracing: Exposure and Travel History</div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">15. Exposure History</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms?  OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                      <select class="form-control" name="expoitem1" id="expoitem1" required>
                                            <option value="1" {{(old('expoitem1') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option id="sexpoitem1_no" class="d-none" value="2" {{(old('expoitem1') == 2) ? 'selected' : ''}}>No</option>
                                            <option id="sexpoitem1_unknown" class="d-none" value="3" {{(old('expoitem1') == 3) ? 'selected' : ''}}>Unknown</option>
                                      </select>
                                    </div>
                                    <div id="divExpoitem1">
                                        <div class="form-group">
                                          <label for=""><span class="text-danger font-weight-bold">*</span>Date of Last Contact/Exposure to COVID-19 Positive Area or Patient</label>
                                          <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" min="{{date('Y-m-d', strtotime('-21 Days'))}}" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont', date('Y-m-d', strtotime('-6 Days')))}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="expoitem2"><span class="text-danger font-weight-bold">*</span>Has the patient been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                        <select class="form-control" name="expoitem2" id="expoitem2" required>
                                          <option id="expoitem2_sno" value="0" {{(old('expoitem2') == 2) ? 'selected' : ''}}>No</option>
                                          <option value="1" {{(old('expoitem2') == 1) ? 'selected' : ''}}>Yes, Local</option>
                                          <option value="2" {{(old('expoitem2') == 2) ? 'selected' : ''}}>Yes, International</option>
                                          <option value="3" {{(old('expoitem2') == 3) ? 'selected' : ''}}>Unknown exposure</option>
                                        </select>
                                    </div>
                                    <div id="divTravelInt">
                                        <div class="form-group">
                                            <label for="intCountry"><span class="text-danger font-weight-bold">*</span>If International Travel, country of origin</label>
                                            <select class="form-control" name="intCountry" id="intCountry">
                                                <option value="" {{(is_null(old('intCountry'))) ? 'selected disabled' : ''}}>Choose...</option>
                                                  @foreach ($countries as $country)
                                                      @if($country != 'Philippines')
                                                          <option value="{{$country}}" {{(old('intCountry') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                      @endif
                                                  @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card mb-3">
                                                    <div class="card-header">Inclusive travel dates</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                  <label for="intDateFrom">From</label>
                                                                  <input type="date" class="form-control" name="intDateFrom" id="intDateFrom" value="{{old('intDateFrom')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="intDateTo">From</label>
                                                                    <input type="date" class="form-control" name="intDateTo" id="intDateTo" value="{{old('intDateTo')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="intWithOngoingCovid">With ongoing COVID-19 community transmission?</label>
                                                    <select class="form-control" name="intWithOngoingCovid" id="intWithOngoingCovid">
                                                        <option value="YES" {{(old('intWithOngoingCovid') == "YES") ? 'selected' : ''}}>Yes</option>
                                                        <option value="NO" {{(old('intWithOngoingCovid') == "NO") ? 'selected' : ''}}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="intVessel">Airline/Sea vessel</label>
                                                          <input type="text" class="form-control" name="intVessel" id="intVessel" value="{{old('intVessel')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intVesselNo">Flight/Vessel Number</label>
                                                            <input type="text" class="form-control" name="intVesselNo" id="intVesselNo" value="{{old('intVesselNo')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateDepart">Date of departure</label>
                                                            <input type="date" class="form-control" name="intDateDepart" id="intDateDepart" value="{{old('intDateDepart')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateArrive">Date of arrival in PH</label>
                                                            <input type="date" class="form-control" name="intDateArrive" id="intDateArrive" value="{{old('intDateArrive')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divTravelLoc" class="localTravelOptions">
                                        <div class="card">
                                            <div class="card-header">
                                                <span class="text-danger font-weight-bold">*</span>If Local Travel, specify travel places (<i>Check all that apply, provide name of facility, address, and inclusive travel dates</i>)
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited1" value="Health Facility" {{(is_array(old('placevisited')) && in_array("Health Facility", old('placevisited'))) ? 'checked' : ''}}>
                                                    Health Facility
                                                  </label>
                                                </div>
                                                <div id="divLocal1" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName1"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName1" id="locName1" value="{{old('locName1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress1"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress1" id="locAddress1" value="{{old('locAddress1', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom1"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom1" id="locDateFrom1" value="{{old('locDateFrom1')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo1"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo1" id="locDateTo1" value="{{old('locDateTo1')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid1"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid1" id="locWithOngoingCovid1">
                                                                <option value="YES" {{(old('locWithOngoingCovid1') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid1') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited2" value="Closed Settings" {{(is_array(old('placevisited')) && in_array("Cloed Settings", old('placevisited'))) ? 'checked' : 'checked'}}>
                                                      Closed Settings
                                                    </label>
                                                </div>
                                                <div id="divLocal2" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName2"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName2" id="locName2" value="{{old('locName2', 'HOME')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress2"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress2" id="locAddress2" value="{{old('locAddress2', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom2"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom2" id="locDateFrom2" value="{{old('locDateFrom2', date('Y-m-d', strtotime('-12 Days')))}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo2"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo2" id="locDateTo2" value="{{old('locDateTo2', date('Y-m-d', strtotime('-6 Days')))}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid2"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid2" id="locWithOngoingCovid2">
                                                                <option value="YES" {{(old('locWithOngoingCovid2') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid2') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited3" value="School" {{(is_array(old('placevisited')) && in_array("School", old('placevisited'))) ? 'checked' : ''}}>
                                                      School
                                                    </label>
                                                </div>
                                                <div id="divLocal3" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName3"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName3" id="locName3" value="{{old('locName3')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress3"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress3" id="locAddress3" value="{{old('locAddress3', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom3"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom3" id="locDateFrom3" value="{{old('locDateFrom3')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo3"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo3" id="locDateTo3" value="{{old('locDateTo3')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid3"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid3" id="locWithOngoingCovid3">
                                                                <option value="YES" {{(old('locWithOngoingCovid3') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid3') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited4" value="Workplace" {{(is_array(old('placevisited')) && in_array("Workplace", old('placevisited'))) ? 'checked' : ''}}>
                                                      Workplace
                                                    </label>
                                                </div>
                                                <div id="divLocal4" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName4"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName4" id="locName4" value="{{old('locName4')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress4"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress4" id="locAddress4" value="{{old('locAddress4', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom4"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom4" id="locDateFrom4" value="{{old('locDateFrom4')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo4"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo4" id="locDateTo4" value="{{old('locDateTo4')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid4"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid4" id="locWithOngoingCovid4">
                                                                <option value="YES" {{(old('locWithOngoingCovid4') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid4') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited5" value="Market" {{(is_array(old('placevisited')) && in_array("Market", old('placevisited'))) ? 'checked' : ''}}>
                                                      Market
                                                    </label>
                                                </div>
                                                <div id="divLocal5" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName5"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName5" id="locName5" value="{{old('locName5')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress5"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress5" id="locAddress5" value="{{old('locAddress5', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom5"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom5" id="locDateFrom5" value="{{old('locDateFrom5')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo5"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo5" id="locDateTo5" value="{{old('locDateTo5')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid5"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid5" id="locWithOngoingCovid5">
                                                                <option value="YES" {{(old('locWithOngoingCovid5') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid5') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited6" value="Social Gathering" {{(is_array(old('placevisited')) && in_array("Social Gathering", old('placevisited'))) ? 'checked' : ''}}>
                                                      Social Gathering
                                                    </label>
                                                </div>
                                                <div id="divLocal6" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName6"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName6" id="locName6" value="{{old('locName6')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress6"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress6" id="locAddress6" value="{{old('locAddress6', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom6"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom6" id="locDateFrom6" value="{{old('locDateFrom6')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo6"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo6" id="locDateTo6" value="{{old('locDateTo6')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid6"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid6" id="locWithOngoingCovid6">
                                                                <option value="YES" {{(old('locWithOngoingCovid6') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid6') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited7" value="Others" {{(is_array(old('placevisited')) && in_array("Others", old('placevisited'))) ? 'checked' : ''}}>
                                                      Others
                                                    </label>
                                                </div>
                                                <div id="divLocal7" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName7"><span class="text-danger font-weight-bold">*</span>Name of Place</label>
                                                              <input class="form-control" type="text" name="locName7" id="locName7" value="{{old('locName7')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress7"><span class="text-danger font-weight-bold">*</span>Location <small>(Municipality/City, Province, Region)</small></label>
                                                                <input class="form-control" type="text" name="locAddress7" id="locAddress7" value="{{old('locAddress7', 'GENERAL TRIAS, CAVITE')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom7"><span class="text-danger font-weight-bold">*</span>From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom7" id="locDateFrom7" value="{{old('locDateFrom7')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo7"><span class="text-danger font-weight-bold">*</span>To</label>
                                                                                <input class="form-control" type="date" name="locDateTo7" id="locDateTo7" value="{{old('locDateTo7')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid7"><span class="text-danger font-weight-bold">*</span>With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid7" id="locWithOngoingCovid7">
                                                                <option value="YES" {{(old('locWithOngoingCovid7') == "YES") ? 'selected' : ''}}>Yes</option>
                                                                <option value="NO" {{(old('locWithOngoingCovid7') == "NO") ? 'selected' : ''}}>No</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited8" value="Transport Service" {{(is_array(old('placevisited')) && in_array("Transport Service", old('placevisited'))) ? 'checked' : ''}}>
                                                      Transport Service
                                                    </label>
                                                </div>
                                                <div id="divLocal8" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel1">1. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel1" id="localVessel1" value="{{old('localVessel1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo1">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo1" id="localVesselNo1" value="{{old('localVesselNo1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin1">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin1" id="localOrigin1" value="{{old('localOrigin1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart1">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart1" id="localDateDepart1" value="{{old('localDateDepart1')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest1">Destination</label>
                                                                <input type="text" class="form-control" name="localDest1" id="localDest1" value="{{old('localDest1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive1">Date of Arrival</label>
                                                                <input type="date" class="form-control" name="localDateArrive1" id="localDateArrive1" value="{{old('localDateArrive1')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel2">2. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel2" id="localVessel2" value="{{old('localVessel2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo2">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo2" id="localVesselNo2" value="{{old('localVesselNo2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin2">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin2" id="localOrigin2" value="{{old('localOrigin2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart2">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart2" id="localDateDepart2" value="{{old('localDateDepart2')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest2">Destination</label>
                                                                <input type="text" class="form-control" name="localDest2" id="localDest2" value="{{old('localDest2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive2">Date of Arrival</label>
                                                                <input type="date" class="form-control" name="localDateArrive2" id="localDateArrive2" value="{{old('localDateArrive2')}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">List Names of Close Contacts</div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                <h4 class="text-danger"><b>NOTE:</b></h4>
                                                <hr>
                                                <p id="ccnote_withsx" class="d-none">Isulat ang mga <b>pangalan at contact number</b> ng mga taong nakasalamuha ng pasyente <b>DALAWANG ARAW BAGO NAGSIMULA ANG SINTOMAS</b> hanggang ngayong araw. / Provide <b>names and contact numbers</b> of persons who were with the patient <b>TWO DAYS PRIOR TO ONSET OF ILLNESS</b> until this date.</p>
                                                <p id="ccnote_nosx" class="d-none">Isulat ang mga <b>pangalan at contact number</b> ng mga taong nakasalamuha ng pasyente sa <b>MISMONG ARAW NA KINOLEKTA ANG KANYANG SPECIMEN</b>. / Provide <b>names and contact numbers</b> of persons who were with the patient <b>ON THE DAY SPECIMEN WAS SUBMITTED FOR TESTING</b> until this date.</p>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">Name</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                              <input type="text" class="form-control" name="contact1Name" id="contact1Name" value="{{old('contact1Name')}}" minlength="5" maxlength="60" style="text-transform: uppercase;" placeholder="Name of Close Contact #1">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2Name" id="contact2Name" value="{{old('contact2Name')}}" minlength="5" maxlength="60" style="text-transform: uppercase;" placeholder="Name of Close Contact #2">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3Name" id="contact3Name" value="{{old('contact3Name')}}" minlength="5" maxlength="60" style="text-transform: uppercase;" placeholder="Name of Close Contact #3">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4Name" id="contact4Name" value="{{old('contact4Name')}}" minlength="5" maxlength="60" style="text-transform: uppercase;" placeholder="Name of Close Contact #4">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">Contact Number</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact1No" id="contact1No" value="{{old('contact1No')}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2No" id="contact2No" value="{{old('contact2No')}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3No" id="contact3No" value="{{old('contact3No')}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4No" id="contact4No" value="{{old('contact3No')}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between">
                                                <div>Link Primary CC of {{$records->getName()}} (To be filled by Contact Tracers ONLY)</div>
                                                <div><button type="button" class="btn btn-outline-success" disabled><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add Primary CC</button></div>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <p>Available after creating the CIF of the Patient.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-block" id="formsubmit"><i class="fas fa-save mr-2"></i>Save (CTRL + S)</button>
                </div>
            </div>
        </form>
        <div class="modal fade bd-example-modal-lg" id="appendix" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Appendix</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div id="accordianId" role="tablist" aria-multiselectable="true">
                            <div class="card">
                                <div class="card-header" role="tab" id="section1HeaderId">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                            Appendix 1. COVID-19 Case Definitions
                                        </a>
                                    </h6>
                                </div>
                                <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                    <div class="card-body">
                                        <div class="card mb-3">
                                            <div class="card-header font-weight-bold">SUSPECT</div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li>A.) A person who meets the <b>clinical AND epidemiological criteria</b></li>
                                                    <li><b>- Clinical criteria:</b></li>
                                                    <ul>
                                                        <li>1.) Acute onset of fever AND cough <b>OR</b></li>
                                                        <li>2.) Acute onset of <b>ANY THREE OR MORE</b> of the following signs of symptoms; fever, cough, general weakness/fatigue, headache, myalgia, sore throat, coryza, dyspnea, anorexia / nausea / vomiting, diarrhea, altered mental status. <b>AND</b></li>
                                                    </ul>
                                                    <li><b>- Epidemiological criteria</b></li>
                                                    <ul>
                                                        <li>1.) Residing/working in an area with high risk of transmission of the virus
                                                            (e.g closed residential settings and humanitarian settings, such as
                                                            camp and camp-like setting for displaced persons), any time w/in the
                                                            14 days prior to symptoms onset <b>OR</b></li>
                                                        <li>Residing in or travel to an area with community transmission anytime
                                                            w/in the 14 days prior to symptoms onset; <b>OR</b></li>
                                                        <li>Working in health setting, including w/in the health facilities and w/in
                                                            households, anytime w/in the 14 days prior to symptom onset; OR</li>
                                                    </ul>
                                                    <li>B.) A patient with <b>severe acute respiratory illness</b> (SARI: acute respiratory
                                                        infection with history of fever or measured fever of ≥ 38°C; cough with
                                                        onset w/in the last 10 days; and who requires hospitalization)</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-header font-weight-bold">PROBABLE</div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li>A.) A <b>patient</b> who meets the <b>clinical criteria</b> (on the top) <b>AND is contact of a probable or
                                                        confirmed case</b>, or <b>epidemiologically linked to a cluster of cases</b> which had had at least one
                                                        confirmed identified within that cluster</li>
                                                    <li>B.) A <b>suspect case</b> (on the top) with <b>chest imaging showing findings suggestive of COVID-19
                                                        disease.</b> Typical chest imaging findings include (Manna, 2020):</li>
                                                    <ul>
                                                        <li>Chest radiography: hazy opacities, often rounded in morphology, with peripheral and lower
                                                            lung distribution</li>
                                                        <li>Chest CT: multiple bilateral ground glass opacities, often rounded in morphology, with
                                                            peripheral and lower lung distribution</li>
                                                        <li>Lung ultrasound: thickened pleural lines, B lines (multifocal, discrete, or confluent),
                                                            consolidative patterns with or without air bronchograms</li>
                                                    </ul>
                                                    <li>C.) A person with <b>recent onset of anosmia (loss of smell), ageusia (loss of taste) in the absence of any other identified cause</b></li>
                                                    <li>D.) Death, not otherwise explained, in an <b>adult with respiratory distress preceding death AND
                                                        who was a contact of a probable or confirmed case or epidemiologically linked to a cluster</b>
                                                        which has had at least one confirmed case identified with that cluster</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header font-weight-bold">CONFIRMED</div>
                                            <div class="card-body">
                                                <p>A person with <b>laboratory confirmation of COVID-19 infection</b>, irrespective of clinical signs and symptoms.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="section2HeaderId">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section2ContentId" aria-expanded="true" aria-controls="section2ContentId">
                                            Appendix 2. Testing Category / Subgroup
                                        </a>
                                    </h6>
                                </div>
                                <div id="section2ContentId" class="collapse in" role="tabpanel" aria-labelledby="section2HeaderId">
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li><b>A.</b> Individuals with severe/critical symptoms and relevant history of travel/contact</li>
                                            <li><b>B.</b> Individuals with <b>mild</b> symptoms, <b>relevant history</b> of travel/contact, and considered
                                                <b>vulnerable</b>; vulnerable populations include those elderly and with preexisting
                                                medical conditions that predispose them to severe presentation and complications
                                                of COVID-19
                                            </li>
                                            <li><b>C.</b> Individuals with <b>mild</b> symptoms, and <b>relevant history</b> of travel and/or contact</li>
                                            <li><b>D.</b> Individuals with <b>no symptoms</b> but with <b>relevant history</b> of travel and/or contact or
                                                high risk of exposure. These include:</li>
                                            <ul>
                                                <li>D1 - <b>Contact-traced individuals</b></li>
                                                <li>D2 - <b>Healthcare workers</b>, who shall be prioritized for regular testing in order to ensure
                                                    the stability of our healthcare system</li>
                                                <li>D3 - <b>Returning Overseas Filipino</b> (ROF) workers, who shall immediately be tested at
                                                    port of entry</li>
                                                <li>D4 - Filipino citizens in a specific locality within the Philippines who have expressed
                                                    intention to return to their place of residence/home origin (<b>Locally Stranded
                                                        Individuals</b>) may be tested subject to the existing protocols of the IATF
                                                    </li>
                                            </ul>
                                            <li><b>E.</b> <b>Frontliners indirectly involved in health care provision</b> in the response against
                                                COVID-19 may be tested as follows:</li>
                                            <ul>
                                                <li>E1 - Those with <b>high or direct exposure to COVID-19 regardless of location</b> may be
                                                    tested up to once a week. These include: <b>(1)</b> Personnel manning the Temporary
                                                    Treatment and Quarantine Facilities (LGU and Nationally-managed); <b>(2)</b> Personnel
                                                    serving at the COVID-19 swabbing center; <b>(3)</b> Contact tracing personnel; and <b>(4)</b>
                                                    Any personnel conducting swabbing for COVID-19 testing.</li>
                                                <li>E2 - Those who <b>do not have high or direct exposure to COVID-19</b> but who <b>live or work
                                                    in Special Concern Areas</b> may be tested up to every two to four weeks. These
                                                    include the following: <b>(1)</b> Personnel manning Quarantine Control Points, including
                                                    those from Armed Forces of the Philippines, Bureau of Fire Protection; <b>(2)</b> National
                                                    / Regional / Local Risk Reduction and Management Teams; <b>(3)</b> Officials from any
                                                    local government / city / municipality health office (CEDSU, CESU, etc.); <b>(4)</b>
                                                    Barangay Health Emergency Response Teams and barangay officials providing
                                                    barangay border control and performing COVID-19-related tasks; <b>(5)</b> Personnel of
                                                    Bureau of Corrections and Bureau of Jail Penology & Management; <b>(6)</b> Personnel
                                                    manning the One-Stop-Shop in the Management of ROFs; <b>(7)</b> Border control or
                                                    patrol officers, such as immigration officers and the Philippine Coast Guard; and <b>(8)</b>
                                                    Social workers providing amelioration and relief assistance to communities and
                                                    performing COVID-19-related tasks.</li>
                                            </ul>
                                            <li><b>F.</b> Other <b>vulnerable patients</b> and those <b>living in confined spaces</b>. These include but
                                                are not limited to: <b>(1)</b> Pregnant patients who shall be tested during the peripartum
                                                period; <b>(2)</b> Dialysis patients; <b>(3)</b> Patients who are immunocompromised, such as
                                                those who have HIV/AIDS, inherited diseases that affect the immune system; <b>(4)</b>
                                                Patients undergoing chemotherapy or radiotherapy; <b>(5)</b> Patients who will undergo
                                                elective surgical procedures with high risk for transmission; <b>(6)</b> Any person who
                                                have had organ transplants, or have had bone marrow or stem cell transplant in
                                                the past 6 months; <b>(7)</b> Any person who is about to be admitted in enclosed
                                                institutions such as jails, penitentiaries, and mental institutions.</li>
                                            <li><b>G.</b> Residents, occupants or workers in a <b>localized area with an active COVID-19
                                                cluster</b>, as identified and declared by the local chief executive in accordance with
                                                existing DOH Guidelines and consistent with the National Task Force Memorandum
                                                Circular No. 02 s.2020 or the Operational Guidelines on the Application of the
                                                Zoning Containment Strategy in the Localization of the National Action Plan Against
                                                COVID-19 Response. The local chief executive shall conduct the necessary testing in
                                                order to protect the broader community and critical economic activities and to
                                                avoid a declaration of a wider community quarantine.</li>
                                            <li><b>H.</b> Frontliners in <b>Tourist Zones</b>: </li>
                                            <ul>
                                                <li>H1 - All workers and employees in the <b>hospitality and tourism sectors</b> in El Nido,
                                                    Boracay, Coron, Panglao, Siargao and other tourist zones, as identified and declared
                                                    by the Department of Tourism. These workers and employees may be tested once
                                                    every four (4) weeks.</li>
                                                <li>H2 - All <b>travelers</b>, whether of domestic or foreign origin, may be tested at least once, at
                                                    their own expense, prior to entry into any designated tourist zone, as identified and
                                                    declared by the Department of Tourism.</li>
                                            </ul>
                                            <li><b>I.</b> All workers and employees of <b>manufacturing companies and public service
                                                providers registered in economic zones</b> located in Special Concern Areas may be
                                                tested regularly.</li>
                                            <li><b>J. Economy Workers</b></li>
                                            <ul>
                                                <li>J1 - <b>Frontline and Economic Priority Workers</b>, defined as those 1) who work in high
                                                    priority sectors, both public and private, 2) have high interaction with and exposure
                                                    to the public, and 3) who live or work in Special Concerns Areas, may be tested
                                                    every three (3) months. These include but not limited to:</li>
                                                <ul>
                                                    <li><b>Transport and Logistics</b>: drivers of taxis, ride hailing services, buses, public
                                                        transport vehicle, conductors, pilots, flight attendants, flight engineers, rail
                                                        operators, mechanics, servicemen, delivery staff, water transport workers (ferries,
                                                        inter-island shipping, ports)</li>
                                                    <li><b>Food Retails</b>: waiters, waitress, bar attendants, baristas, chefs, cooks, restaurant
                                                        managers, supervisors</li>
                                                    <li><b>Education</b>: teachers at all levels of education and other school frontliners such as
                                                        guidance counselors, librarians, cashiers</li>
                                                    <li><b>Financial Services</b>: bank tellers</li>
                                                    <li><b>Non-Food Retails</b>: cashiers, stock clerks, retail salespersons</li>
                                                    <li><b>Services</b>: hairdressers, barbers, manicurists, pedicurists, massage therapists,
                                                        embalmers, morticians, undertakers, funeral directors, parking lot attendants,
                                                        security guards, messengers</li>
                                                    <li><b>Construction</b>: construction workers including carpenters, stonemasons,
                                                        electricians, painters, foremen, supervisors, civil engineers, structural engineers,
                                                        construction managers, crane/tower operators, elevator installers, repairmen</li>
                                                    <li><b>Water Supply, Sewerage, Waster Management</b>: plumbers, recycling/ reclamation
                                                        workers, garbage collectors, water/wastewater engineers, janitors, cleaners</li>
                                                    <li><b>Public Sector</b>: judges, courtroom clerks, staff and security, all national and local
                                                        government employees rendering frontline services in special concern areas</li>
                                                    <li><b>Mass Media</b>: field reporters, photographers, cameramen</li>
                                                </ul>
                                                <li>J2 - All employees <b>not covered above are not required to undergo testing but are
                                                    encouraged to be tested every quarter.</b> Private sector employers are highly
                                                    encouraged to send their employees for regular testing at the employers’ expense
                                                    in order to avoid lockdowns that may do more damage to their companies.</li>
                                            </ul>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="section3HeaderId">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section3ContentId" aria-expanded="true" aria-controls="section3ContentId">
                                            Appendix 3. Severity of the Disease
                                        </a>
                                    </h6>
                                </div>
                                <div id="section3ContentId" class="collapse in" role="tabpanel" aria-labelledby="section3HeaderId">
                                    <div class="card-body">
                                        <div class="card mb-3">
                                            <div class="card-header font-weight-bold">MILD</div>
                                            <div class="card-body">
                                                <p>Symptomatic patients presenting with fever, cough, fatigue, anorexia,
                                                    myalgias; other non-specific symptoms such as sore throat, nasal
                                                    congestion, headache, diarrhea, nausea and vomiting; loss of smell
                                                    (anosmia) or loss of taste (ageusia) preceding the onset of respiratory
                                                    symptoms with <b>NO signs of pneumonia or hypoxia</b></p>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-header font-weight-bold">MODERATE</div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li>
                                                        Adolescent or adult with <b>clinical signs of non-severe pneumonia</b> (e.g.
                                                        fever, cough, dyspnea, respiratory rate <b>(RR) = 21-30 breaths/minute</b>,
                                                        peripheral capillary oxygen saturation (SpO2) >92% on room air).
                                                    </li>
                                                    <li>
                                                        Child with clinical signs of non-severe pneumonia (cough or difficulty of
                                                        breathing and fast breathing [ < 2 months: > 60; 2-11 months: > 50; 1-5
                                                        years: > 40] and/or chest indrawing)
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-header font-weight-bold">SEVERE</div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li>Adolescent or adult with <b>clinical signs of severe pneumonia or severe
                                                        acute respiratory infection</b> as follows: fever, cough, dyspnea, <b>RR>30
                                                        breaths/minute</b>, severe respiratory distress or SpO2 < 92% on room air</li>
                                                    <li>Child with clinical signs of pneumonia (cough or difficulty in breathing)
                                                        plus at least one of the following:</li>
                                                    <ul>
                                                        <li>a. Central cyanosis or SpO2 < 90%; severe <b>respiratory distress</b> (e.g. fast
                                                            breathing, grunting, very severe chest indrawing); general danger sign:
                                                            <b>inability to breastfeed or drink, lethargy or unconsciousness</b>, or
                                                            convulsions.</li>
                                                        <li><b>Fast breathing (in breaths/min): < 2 months: > 60; 2-11 months: > 50;
                                                            1-5 years: > 40.</b></li>
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header font-weight-bold">CRITICAL</div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    <li>Patients manifesting with acute respiratory distress syndrome, sepsis and/or septic shock:</li>
                                                    <li>1. <b>Acute Respiratory Distress Syndrome (ARDS)</b></li>
                                                    <ul>
                                                        <li>a. Patients with onset within 1 week of known clinical insult (pneumonia) or new or worsening
                                                            respiratory symptoms, progressing infiltrates on chest X-ray or chest CT scan, with respiratory
                                                            failure not fully explained by cardiac failure or fluid overload.</li>
                                                    </ul>
                                                    <li>2. <b>Sepsis</b></li>
                                                    <ul>
                                                        <li>a. Adults with life-threatening organ dysfunction caused by a dysregulated host response to
                                                            suspected or proven infection. Signs of organ dysfunction include altered mental status, difficult
                                                            or fast breathing, low oxygen saturation, reduced urine output, fast heart rate, weak pulse, cold
                                                            extremities or low blood pressure, skin mottling, or laboratory evidence of coagulopathy,
                                                            thrombocytopenia, acidosis, high lactate or hyperbilirubinemia.</li>
                                                        <li>b. Children with suspected or proven infection and > 2 age-based systemic inflammatory response
                                                            syndrome criteria (abnormal temperature [> 38.5 °C or < 36 °C); tachycardia for age or
                                                            bradycardia for age if < 1year; tachypnea for age or need for mechanical ventilation; abnormal
                                                            white blood cell count for age or > 10% bands), of which one must be abnormal temperature or
                                                            white blood cell count.</li>
                                                    </ul>
                                                    <li>3. <b>Septic Shock</b></li>
                                                    <ul>
                                                        <li>a. Adults with persistent hypotension despite volume resuscitation, requiring vasopressors to
                                                            maintain MAP > 65 mmHg and serum lactate level >2mmol/L</li>
                                                        <li>b. Children with any hypotension (SBP < Sth centile or > 2 SD below normal for age) or two or three
                                                            of the following: altered mental status; bradycardia or tachycardia (HR < 90 bpm or > 160 bpm in
                                                            infants and heart rate < 70 bpm or > 150 bpm in children); prolonged capillary refill (> 2 sec) or
                                                            weak pulse; fast breathing; mottled or cool skin or petechial or purpuric rash; high lactate;
                                                            reduced urine output; hyperthermia or hypothermia.</li>
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#formsubmit').trigger('click');
                $('#formsubmit').prop('disabled', true);
                setTimeout(function() {
                    $('#formsubmit').prop('disabled', false);
                }, 2000);
                return false;
            }
        });
        
        $('#formsubmit').click(function (e) { 
            if($('#caseClassification').val() == 'Confirmed') {
                confirm('You are encoding {{$records->getName()}} as a POSITIVE (+) Case. Please double check carefully and Click OK to Confirm.');
            }
            else if($('#caseClassification').val() == 'Non-COVID-19 Case') {
                confirm('You are encoding {{$records->getName()}} as a NEGATIVE (-) Case. Please double check carefully and Click OK to Confirm.');
            }
        });

        $(document).ready(function () {
            @if(is_null(auth()->user()->brgy_id) && is_null(auth()->user()->company_id))
            $('#interviewerName').selectize();
            @endif

            $('#testingCat').select2({
                theme: "bootstrap",
            });

            $('#informantName').keydown(function (e) { 
                if($(this).val().length <= 0 || $(this).val() == "") {
                    $('#informantRelationship').prop({disabled: true, required: false});
                    $('#informantMobile').prop({disabled: true, required: false});
                }
                else {
                    $('#informantRelationship').val("");
                    $('#informantRelationship').prop({disabled: false, required: true});
                    $('#informantMobile').prop({disabled: false, required: true});
                }
            }).trigger('keydown');

            //For Reinfection
            $('#caseClassification').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 'Confirmed') {
                    $('#askIfReinfected').show();
                    $('#confirmedVariant').show();

                    //First Type of Swab Test Will be Required and Set to Positive
                    $('#testType1').prop('required', true);
                    $('#testResult1').val("POSITIVE");
                    $('#testResult1').trigger('change');
                    $('#tro1_pending').addClass('d-none');
                    $('#tro1_positive').removeClass('d-none');
                    $('#tro1_negative').addClass('d-none');
                    $('#tro1_equivocal').addClass('d-none');
                    $('#tro1_others').addClass('d-none');

                    $('#testResult2').val("POSITIVE");
                    $('#tro2_pending').addClass('d-none');
                    $('#tro2_positive').removeClass('d-none');
                    $('#tro2_negative').addClass('d-none');
                    $('#tro2_equivocal').addClass('d-none');
                    $('#tro2_others').addClass('d-none');
                }
                else if($(this).val() == 'Non-COVID-19 Case') {
                    $('#cutoffwarning').removeClass('d-none');
                    $('#askIfReinfected').hide();
                    $('#confirmedVariant').hide();

                    $('#testType1').prop('required', true);
                    $('#testResult1').val("NEGATIVE");
                    $('#testResult1').trigger('change');
                    $('#tro1_pending').addClass('d-none');
                    $('#tro1_positive').addClass('d-none');
                    $('#tro1_negative').removeClass('d-none');
                    $('#tro1_equivocal').addClass('d-none');
                    $('#tro1_others').addClass('d-none');

                    $('#testResult2').val("NEGATIVE");
                    $('#tro2_pending').addClass('d-none');
                    $('#tro2_positive').addClass('d-none');
                    $('#tro2_negative').removeClass('d-none');
                    $('#tro2_equivocal').addClass('d-none');
                    $('#tro2_others').addClass('d-none');
                }
                else {
                    $('#askIfReinfected').hide();
                    $('#confirmedVariant').hide();

                    $('#testType1').prop('required', false);
                    //$('#testResult1').val("PENDING");
                    //$('#testResult1').trigger('change');
                    $('#tro1_pending').removeClass('d-none');
                    $('#tro1_positive').removeClass('d-none');
                    $('#tro1_negative').removeClass('d-none');
                    $('#tro1_equivocal').removeClass('d-none');
                    $('#tro1_others').removeClass('d-none');
                    
                    //$('#testResult2').val("PENDING");
                    $('#tro2_pending').removeClass('d-none');
                    $('#tro2_positive').removeClass('d-none');
                    $('#tro2_negative').removeClass('d-none');
                    $('#tro2_equivocal').removeClass('d-none');
                    $('#tro2_others').removeClass('d-none');
                }
            }).trigger('change');
            
            $('#ecothers').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divECOthers').show();
                    $('#ecOthersRemarks').prop('required', true);
                }
                else {
                    $('#divECOthers').hide();
                    $('#ecOthersRemarks').prop('required', false);
                }
            });

            $(function(){
                var requiredCheckboxes = $('.exCaseList :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            $(function(){
                var requiredCheckboxes = $('.imaOptions :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            $(function(){
                var requiredCheckboxes = $('.comoOpt :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            $(function(){
                var requiredCheckboxes = $('.labOptions :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            $(function(){
                var requiredCheckboxes = $('.localTravelOptions :checkbox');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            var getCurrentPtype = $('#pType').val();
            var getCurrentExpo1 = $('#expoitem1').val();
            var getCurrentExpo2 = $('#expoitem2').val();

            $(function(){
                var requiredCheckboxes = $(".symptomsList :checkbox");
                requiredCheckboxes.change(function() {
                    if(requiredCheckboxes.is(':checked')) {
                        $('#onsetasterisk').removeClass('d-none');
                        $('#dateOnsetOfIllness').prop('required', true);
                        $('#pType').val('PROBABLE');
                        $('#expoitem1').val('1').change();
                        $('#sexpoitem1_no').addClass('d-none');
                        $('#sexpoitem1_unknown').addClass('d-none');

                        $('#expoitem2').val('1').change();
                        $('#expoitem2_sno').addClass('d-none');

                        $('#ccnote_withsx').removeClass('d-none');
                        $('#ccnote_nosx').addClass('d-none');
                    } else {
                        $('#onsetasterisk').addClass('d-none');
                        $('#dateOnsetOfIllness').prop('required', false);
                        $('#pType').val(getCurrentPtype);
                        $('#expoitem1').val(getCurrentExpo1).change();
                        $('#sexpoitem1_no').removeClass('d-none');
                        $('#sexpoitem1_unknown').removeClass('d-none');

                        $('#expoitem2').val(getCurrentExpo2).change();
                        $('#expoitem2_sno').removeClass('d-none');

                        $('#ccnote_withsx').addClass('d-none');
                        $('#ccnote_nosx').removeClass('d-none');
                    }
                }).trigger('change');
            });

            $('#LSICity').prop({'disabled': true, 'required': false});

            $.getJSON("{{asset('json/refregion.json')}}", function(data) {
                var sorted = data.sort(function(a, b) {
                    if (a.regDesc > b.regDesc) {
                    return 1;
                    }
                    if (a.regDesc < b.regDesc) {
                    return -1;
                    }
                    return 0;
                });
                $.each(sorted, function(key, val) {
                    $("#sfacilityregion").append('<option value="'+val.regCode+'">'+val.regDesc+'</option>');
                });
            });

            $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
                var sorted = data.sort(function(a, b) {
                    if (a.provDesc > b.provDesc) {
                    return 1;
                    }
                    if (a.provDesc < b.provDesc) {
                    return -1;
                    }
                    return 0;
                });
                $.each(sorted, function(key, val) {
                    $("#LSIProvince").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
                });
            });

            $('#sfacilityregion').change(function (e) {
                e.preventDefault();
                $('#facilityprovince').prop({'disabled': false, 'required': true});
                $('#facilityprovince').empty();
                $("#facilityprovince").append('<option value="" selected disabled>Choose...</option>');

                $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.provDesc > b.provDesc) {
                        return 1;
                        }
                        if (a.provDesc < b.provDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#sfacilityregion').val() == val.regCode) {
                            $("#facilityprovince").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
                        }
                    });
			    });
            });

            $('#LSIProvince').change(function (e) { 
                e.preventDefault();
                $('#LSICity').prop({'disabled': false, 'required': true});
                $('#LSICity').empty();
                $("#LSICity").append('<option value="" selected disabled>Choose...</option>');
                $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.citymunDesc > b.citymunDesc) {
                        return 1;
                        }
                        if (a.citymunDesc < b.citymunDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#LSIProvince').val() == val.provCode) {
                            $("#LSICity").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
                        }
                    });
			    });
            });

            //$('#OFWCountyOfOrigin').selectize();
            //$('#FNTCountryOfOrigin').selectize();
        
            $('#divYes1').hide();
            $('#divYes5').hide();
            $('#divYes6').hide();
            
            $('#dispositionDate').prop("type", "datetime-local");

            $('#havePreviousCovidConsultation').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '1') {
                    $('#divYes1').show();

                    $('#dateOfFirstConsult').prop('required', true);
                    $('#facilityNameOfFirstConsult').prop('required', true);
                }
                else {
                    $('#divYes1').hide();

                    $('#dateOfFirstConsult').prop('required', false);
                    $('#facilityNameOfFirstConsult').prop('required', false);
                }
            }).trigger('change');

            @if($records->isPregnant == 1)
            var is_pregnant = 1;
            @else
            var is_pregnant = 2;
            @endif

            $('#dispositionType').change(function (e) {
                e.preventDefault();
                $('#dispositionDate').prop("type", "datetime-local");

                if($('#isForHospitalization').val() == 1) {
                    $('#dispositionType').val('5');
                    $('#disopt1').addClass('d-none');
                    $('#disopt2').addClass('d-none');
                    $('#disopt3').addClass('d-none');
                    $('#disopt4').addClass('d-none');
                    $('#disopt6').addClass('d-none');
                    $('#disopt7').addClass('d-none');

                    /*
                    if(is_pregnant == 1) {
                        $('#dispositionName').val('FOR DELIVERY');
                    }
                    */
                }
                else {
                    $('#dispositionType').val('3');

                    $('#disopt1').removeClass('d-none');
                    $('#disopt2').removeClass('d-none');
                    $('#disopt3').removeClass('d-none');
                    $('#disopt4').removeClass('d-none');
                    $('#disopt6').removeClass('d-none');
                    $('#disopt7').removeClass('d-none');
                }
                
                if($(this).val() == '1' || $(this).val() == '2') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '3' || $(this).val() == '4') {
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '5') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', false);
                }
                else if ($(this).val() == '6') {
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', true);
                }
                else if($(this).val().length == 0){
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', false);
                }

                if($(this).val() == '1') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Hospital");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                else if($(this).val() == '2') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Facility");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                else if($(this).val() == '3') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
                }
                else if($(this).val() == '4') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositionDate').prop("type", "date");

                    $('#dispositiondatelabel').text("Date of Discharge");
                }
                else if($(this).val() == '5') {
                    $('#divYes5').show();
                    $('#divYes6').hide();

                    $('#dispositionlabel').text("State Reason");
                }
                else if($(this).val() == '6') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositiondatelabel').text("Date and Time Started");
                }
                else if($(this).val().length == 0){
                    $('#divYes5').hide();
                    $('#divYes6').hide();
                }
            }).trigger('change');

            $('#isHealthCareWorker').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '0') {
                    $('#divisHealthCareWorker').hide();
                    $('#healthCareCompanyName').prop('required', false);
                    $('#healthCareCompanyLocation').prop('required', false);
                }
                else {
                    $('#divisHealthCareWorker').show();
                    $('#healthCareCompanyName').prop('required', true);
                    $('#healthCareCompanyLocation').prop('required', true);
                }
            }).trigger('change');

            $('#isOFW').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisOFW').hide();
                    $('#OFWCountyOfOrigin').prop('required', false);
                    $('#OFWPassportNo').prop('required', false);
                }
                else {
                    $('#divisOFW').show();
                    $('#OFWPassportNo').prop('required', true);
                    $('#oaddressscountry').val('N/A');
                    $('#OFWCountyOfOrigin').prop('required', true);
                }
            }).trigger('change');

            $('#OFWCountyOfOrigin').change(function (e) { 
                e.preventDefault();
                $('#oaddressscountry').val($(this).val());
            });

            $('#isFNT').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisFNT').hide();
                    $('#FNTCountryOfOrigin').prop('required', false);
                    $('#FNTPassportNo').prop('required', false);
                }
                else {
                    $('#divisFNT').show();
                    $('#FNTCountryOfOrigin').prop('required', true);
                    $('#FNTPassportNo').prop('required', true);
                }
            }).trigger('change');

            $('#isLSI').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisLSI').hide();
                    $('#LSIProvince').prop('required', false);
                    $('#LSICity').prop('required', false);
                }
                else {
                    $('#divisLSI').show();
                    $('#LSIProvince').prop('required', true);
                    $('#LSICity').prop('required', true);
                }
            }).trigger('change');

            $('#isLivesOnClosedSettings').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisLivesOnClosedSettings').hide();
                    $('#institutionType').prop('required', false);
                    $('#institutionName').prop('required', false);
                }
                else {
                    $('#divisLivesOnClosedSettings').show();
                    $('#institutionType').prop('required', true);
                    $('#institutionName').prop('required', true);
                }
            }).trigger('change');

            $('#signsCheck2').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divFeverChecked').show();
                    $('#SASFeverDeg').prop('required', true);
                }
                else {
                    $('#divFeverChecked').hide();
                    $('#SASFeverDeg').prop('required', false);
                }
            }).trigger('change');

            $('#signsCheck18').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divSASOtherChecked').show();
                    $('#SASOtherRemarks').prop('required', true);
                }
                else {
                    $('#divSASOtherChecked').hide();
                    $('#SASOtherRemarks').prop('required', false);
                }
            }).trigger('change');

            $('#comCheck10').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divComOthersChecked').show();
                    $('#COMOOtherRemarks').prop('required', true);
                }
                else {
                    $('#divComOthersChecked').hide();
                    $('#COMOOtherRemarks').prop('required', false);
                }
            }).trigger('change');
            
            $('#comCheck1').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#comCheck2').prop({'disabled': true, 'checked': false});
                    $('#comCheck3').prop({'disabled': true, 'checked': false});
                    $('#comCheck4').prop({'disabled': true, 'checked': false});
                    $('#comCheck5').prop({'disabled': true, 'checked': false});
                    $('#comCheck6').prop({'disabled': true, 'checked': false});
                    $('#comCheck7').prop({'disabled': true, 'checked': false});
                    $('#comCheck8').prop({'disabled': true, 'checked': false});
                    $('#comCheck9').prop({'disabled': true, 'checked': false});
                    $('#comCheck10').prop({'disabled': true, 'checked': false});
                    $('#comCheck11').prop({'disabled': true, 'checked': false});
                    $('#comCheck12').prop({'disabled': true, 'checked': false});
                    $('#comCheck13').prop({'disabled': true, 'checked': false});
                }
                else {
                    $('#comCheck2').prop({'disabled': false, 'checked': false});
                    $('#comCheck3').prop({'disabled': false, 'checked': false});
                    $('#comCheck4').prop({'disabled': false, 'checked': false});
                    $('#comCheck5').prop({'disabled': false, 'checked': false});
                    $('#comCheck6').prop({'disabled': false, 'checked': false});
                    $('#comCheck7').prop({'disabled': false, 'checked': false});
                    $('#comCheck8').prop({'disabled': false, 'checked': false});
                    $('#comCheck9').prop({'disabled': false, 'checked': false});
                    $('#comCheck10').prop({'disabled': false, 'checked': false});
                    $('#comCheck11').prop({'disabled': false, 'checked': false});
                    $('#comCheck12').prop({'disabled': false, 'checked': false});
                    $('#comCheck13').prop({'disabled': false, 'checked': false});
                }
            });

            @if(is_null(old('comCheck')))
                $('#comCheck1').prop('checked', true);
            @endif

            $('#imagingDone').change(function (e) { 
                e.preventDefault();
                $('#divImagingOthers').hide();
                $('#imagingOtherFindings').val("");
                if($(this).val() == "None") {
                    $('#imagingDoneDate').prop({disabled: true, required: false});
                    $('#imagingResult').prop({disabled: true, required: false});
                    $("#imagingResult").empty();
                }
                else {
                    $('#imagingDoneDate').prop({disabled: false, required: true});
                    $('#imagingResult').prop({disabled: false, required: true});
                    $("#imagingResult").empty();
                    $("#imagingResult").append(new Option("Normal", "NORMAL"));
                    $("#imagingResult").append(new Option("Pending", "PENDING"));

                    $('#divImagingOthers').hide();

                    if($(this).val() == "Chest Radiography") {
                        $("#imagingResult").append(new Option("Hazy opacities, often rounded in morphology, with peripheral and lower lung dist.", "HAZY"));
                    }
                    else if($(this).val() == "Chest CT") {
                        $("#imagingResult").append(new Option("Multiple bilateral ground glass opacities, often rounded in morphology, w/ peripheral and lower lung dist.", "MULTIPLE"));
                    }
                    else if($(this).val() == "Lung Ultrasound") {
                        $("#imagingResult").append(new Option("Thickened pleural lines, B lines, consolidative patterns with or without air bronchograms.", "THICKENED"));
                    }
                    
                    if($(this).val() != "OTHERS") {
                        $("#imagingResult").append(new Option("Other findings", "OTHERS"));
                    }
                }
            }).trigger('change');

            $('#imagingResult').change(function (e) { 
                e.preventDefault();
                $('#imagingOtherFindings').val("");
                if($(this).val() == "OTHERS") {
                    $('#divImagingOthers').show();
                    $('imagingOtherFindings').prop({disabled: false, required: true});
                }
                else {
                    $('#divImagingOthers').hide();
                    $('imagingOtherFindings').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#testType1').change(function (e) { 
                e.preventDefault();
                if($(this).val() === "") {
                    $('#testDateCollected1').prop('required', false);
                    $('#testResult1').prop('required', false);

                    $('#divTypeOthers1').addClass('d-none');
                    $('#testTypeOtherRemarks1').empty();
                    $('#testTypeOtherRemarks1').prop('required', false);

                    $('#ifAntigen1').addClass('d-none');
                    $('#antigen_id1').prop('required', false);

                    $('#testResult1').prop('disabled', true);
                    $('#testDateCollected1').prop('disabled', true);
                    $('#oniTimeCollected1').prop('disabled', true);
                    $('#testLaboratory1').prop('disabled', true);
                    $('#testDateReleased1').prop('disabled', true);
                }
                else {
                    $('#testDateCollected1').prop('required', true);
                    $('#testResult1').prop('required', true);

                    $('#testResult1').prop('disabled', false);
                    $('#testDateCollected1').prop('disabled', false);
                    $('#oniTimeCollected1').prop('disabled', false);
                    $('#testLaboratory1').prop('disabled', false);
                    $('#testDateReleased1').prop('disabled', false);

                    if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                        $('#divTypeOthers1').removeClass('d-none');
                        $('#testTypeOtherRemarks1').prop('required', true);

                        if($(this).val() == 'ANTIGEN') {
                            $('#ifAntigen1').removeClass('d-none');
                            $('#antigen_id1').prop('required', true);
                            $('#testLaboratory1').val('CHO GENERAL TRIAS');
                        }
                        else {
                            $('#ifAntigen1').addClass('d-none');
                            $('#antigen_id1').prop('required', false);
                            $('#testLaboratory1').val('');
                        }
                    }
                    else {
                        $('#divTypeOthers1').addClass('d-none');
                        $('#testTypeOtherRemarks1').empty();
                        $('#testTypeOtherRemarks1').prop('required', false);

                        $('#ifAntigen1').addClass('d-none');
                        $('#antigen_id1').prop('required', false);
                    }
                }
            }).trigger('change');

            //Get Default Case Classification
            var defcc = $('#caseClassification').val();

            $('#testResult1').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "OTHERS") {
                    $('#divResultOthers1').show();
                    $('#testResultOtherRemarks1').prop('required', true);
                    $('#testDateReleased1').prop('required', true);
                }
                else {
                    $('#divResultOthers1').hide();
                    $('#testResultOtherRemarks1').empty();
                    $('#testResultOtherRemarks1').prop('required', false);

                    if($(this).val() == "POSITIVE" || $(this).val() == "NEGATIVE" || $(this).val() == "EQUIVOCAL") {
                        $('#testDateReleased1').prop('required', true);
                        $('#ifDateReleased1').removeClass('d-none');

                        $('#testLaboratory1').prop('required', true);
                        $('#reql1').removeClass('d-none');
                    }
                    else {
                        $('#testDateReleased1').val('');
                        $('#testDateReleased1').prop('required', false);
                        $('#ifDateReleased1').addClass('d-none');

                        $('#testLaboratory1').prop('required', false);
                        $('#reql1').addClass('d-none');
                    }
                }

                //Antigen Required Fields
                if($('#testType1').val() == 'ANTIGEN') {
                    if($(this).val() != 'PENDING') {
                        $('#reqtc1').removeClass('d-none');
                        $('#oniTimeCollected1').prop('required', true);
                    }
                    else {
                        $('#reqtc1').addClass('d-none');
                        $('#oniTimeCollected1').prop('required', false);
                    }
                }
                else {
                    $('#reqtc1').addClass('d-none');
                    $('#oniTimeCollected1').prop('required', false);
                }

                if($(this).val() == 'POSITIVE') {
                    if($('#caseClassification').val() != 'Confirmed') {
                        if($('#testType1').val() != 'ANTIGEN') {
                            $('#caseClassification').val('Confirmed');
                        }
                        else {
                            $('#caseClassification').val('Probable');
                        }
                        $('#caseClassification').trigger('change');
                    }
                }
                else if($(this).val() == 'NEGATIVE') {
                    if($('#caseClassification').val() != 'Non-COVID-19 Case') {
                        $('#caseClassification').val('Non-COVID-19 Case');
                        $('#caseClassification').trigger('change');
                    }
                }
                else {
                    if($('#caseClassification').val() != defcc) {
                        $('#caseClassification').val(defcc);
                        $('#caseClassification').trigger('change');
                    }
                }
            }).trigger('change');

            $('#testType2').change(function (e) { 
                e.preventDefault();
                if($(this).val() === "") {
                    $('#testDateCollected2').prop('required', false);
                    $('#testResult2').prop('required', false);

                    $('#divTypeOthers2').addClass('d-none');
                    $('#testTypeOtherRemarks2').empty();
                    $('#testTypeOtherRemarks2').prop('required', false);

                    $('#ifAntigen2').addClass('d-none');
                    $('#antigen_id2').prop('required', false);

                    $('#testResult2').prop('disabled', true);
                    $('#testDateCollected2').prop('disabled', true);
                    $('#oniTimeCollected2').prop('disabled', true);
                    $('#testLaboratory2').prop('disabled', true);
                    $('#testDateReleased2').prop('disabled', true);
                }
                else {
                    $('#testDateCollected2').prop('required', true);
                    $('#testResult2').prop('required', true);

                    $('#testResult2').prop('disabled', false);
                    $('#testDateCollected2').prop('disabled', false);
                    $('#oniTimeCollected2').prop('disabled', false);
                    $('#testLaboratory2').prop('disabled', false);
                    $('#testDateReleased2').prop('disabled', false);

                    if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                        $('#divTypeOthers2').removeClass('d-none');
                        $('#testTypeOtherRemarks2').prop('required', true);
                        $('#testDateCollected2').prop('required', true);

                        if($(this).val() == 'ANTIGEN') {
                            $('#ifAntigen2').removeClass('d-none');
                            $('#antigen_id2').prop('required', true);
                            $('#testLaboratory2').val('CHO GENERAL TRIAS');
                        }
                        else {
                            $('#ifAntigen2').addClass('d-none');
                            $('#antigen_id2').prop('required', false);
                            $('#testLaboratory2').val('');
                        }
                    }
                    else {
                        $('#divTypeOthers2').addClass('d-none');
                        $('#testTypeOtherRemarks2').empty();
                        $('#testTypeOtherRemarks2').prop('required', false);

                        $('#ifAntigen2').addClass('d-none');
                        $('#antigen_id2').prop('required', false);
                    }
                }
            }).trigger('change');

            $('#testResult2').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "OTHERS") {
                    $('#divResultOthers2').show();
                    $('#testResultOtherRemarks2').prop('required', true);
                    $('#testDateReleased2').prop('required', true);
                }
                else {
                    $('#divResultOthers2').hide();
                    $('#testResultOtherRemarks2').empty();
                    $('#testResultOtherRemarks2').prop('required', false);

                    if($(this).val() == "POSITIVE" || $(this).val() == "NEGATIVE" || $(this).val() == "EQUIVOCAL") {
                        $('#testDateReleased2').prop('required', true);
                        $('#ifDateReleased2').removeClass('d-none');

                        $('#testLaboratory2').prop('required', true);
                        $('#reql2').removeClass('d-none');
                    }
                    else {
                        $('#testDateReleased2').val('');
                        $('#testDateReleased2').prop('required', false);
                        $('#ifDateReleased2').addClass('d-none');

                        $('#testLaboratory2').prop('required', false);
                        $('#reql2').addClass('d-none');
                    }
                }

                //Antigen Required Fields
                if($('#testType2').val() == 'ANTIGEN') {
                    if($(this).val() != 'PENDING') {
                        $('#reqtc2').removeClass('d-none');
                        $('#oniTimeCollected2').prop('required', true);
                    }
                    else {
                        $('#reqtc2').addClass('d-none');
                        $('#oniTimeCollected2').prop('required', false);
                    }
                }
                else {
                    $('#reqtc1').addClass('d-none');
                    $('#oniTimeCollected2').prop('required', false);
                }

                if($(this).val() == 'POSITIVE') {
                    if($('#caseClassification').val() != 'Confirmed') {
                        if($('#testType2').val() != 'ANTIGEN') {
                            $('#caseClassification').val('Confirmed');
                        }
                        else {
                            $('#caseClassification').val('Probable');
                        }
                        $('#caseClassification').trigger('change');
                    }
                }
                else if($(this).val() == 'NEGATIVE') {
                    if($('#caseClassification').val() != 'Non-COVID-19 Case') {
                        $('#caseClassification').val('Non-COVID-19 Case');
                        $('#caseClassification').trigger('change');
                    }
                }
                else {
                    if($('#caseClassification').val() != defcc) {
                        $('#caseClassification').val(defcc);
                        $('#caseClassification').trigger('change');
                    }
                }
            }).trigger('change');

            $('#testedPositiveUsingRTPCRBefore').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#divIfTestedPositiveUsingRTPCR').show();
                    $('#testedPositiveLab').prop('required', true);
                    $('#testedPositiveSpecCollectedDate').prop('required', true);
                }
                else {
                    $('#divIfTestedPositiveUsingRTPCR').hide();
                    $('#testedPositiveLab').prop('required', false);
                    $('#testedPositiveSpecCollectedDate').prop('required', false);
                }
            }).trigger('change');

            $('#outcomeCondition').change(function (e) { 
                e.preventDefault();
                //Outcome Warning Text
                if($(this).val() == 'Recovered' || $(this).val() == 'Died') {
                    $('#outcomeWarningText').removeClass('d-none');
                }
                else {
                    $('#outcomeWarningText').addClass('d-none');
                }

                if($(this).val() == 'Recovered') {
                    $('#ifOutcomeRecovered').show();
                    $('#outcomeRecovDate').prop('required', true);
                    $('#ifOutcomeDied').hide();
                    $('#outcomeDeathDate').prop('required', false);
                    $('#deathImmeCause').prop('required', false);
                    $('#deathAnteCause').prop('required', false);
                    $('#deathUndeCause').prop('required', false);
                    $('#contriCondi').prop('required', false);
                }
                else if($(this).val() == 'Died') {
                    $('#ifOutcomeRecovered').hide();
                    $('#outcomeRecovDate').prop('required', false);
                    $('#ifOutcomeDied').show();
                    $('#outcomeDeathDate').prop('required', true);
                    $('#deathImmeCause').prop('required', true);
                    $('#deathAnteCause').prop('required', false);
                    $('#deathUndeCause').prop('required', false);
                    $('#contriCondi').prop('required', false);
                }
                else {
                    $('#ifOutcomeRecovered').hide();
                    $('#outcomeRecovDate').prop('required', false);
                    $('#ifOutcomeDied').hide();
                    $('#outcomeDeathDate').prop('required', false);
                    $('#deathImmeCause').prop('required', false);
                    $('#deathAnteCause').prop('required', false);
                    $('#deathUndeCause').prop('required', false);
                    $('#contriCondi').prop('required', false);
                }
            }).trigger('change');

            $('#expoitem1').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#divExpoitem1').show();
                    $('#expoDateLastCont').prop('required', true);
                }
                else {
                    $('#divExpoitem1').hide();
                    $('#expoDateLastCont').val(null);
                    $('#expoDateLastCont').prop('required', false);
                }
            }).trigger('change');

            $('#expoitem2').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 0 || $(this).val() == 3) {
                    $('#divTravelInt').hide();
                    $('#divTravelLoc').hide();

                    $('.localTravelOptions :checkbox').removeAttr('required');
                }
                else if($(this).val() == 1) {
                    $('#divTravelInt').hide();

                    $('#intCountry').prop('required', false);
                    $('#intDateFrom').prop('required', false);
                    $('#intDateTo').prop('required', false);
                    $('#intWithOngoingCovid').prop('required', false);
                    $('#intVessel').prop('required', false);
                    $('#intVesselNo').prop('required', false);
                    $('#intDateDepart').prop('required', false);
                    $('#intDateArrive').prop('required', false);
                    
                    $('#divTravelLoc').show();
                    if(!($('.localTravelOptions :checkbox').is(':checked'))) {
                        $('.localTravelOptions :checkbox').attr('required', 'required');
                    }
                }
                else if($(this).val() == 2) {
                    $('#divTravelInt').show();

                    $('#intCountry').prop('required', true);
                    $('#intDateFrom').prop('required', false);
                    $('#intDateTo').prop('required', false);
                    $('#intWithOngoingCovid').prop('required', false);
                    $('#intVessel').prop('required', false);
                    $('#intVesselNo').prop('required', false);
                    $('#intDateDepart').prop('required', false);
                    $('#intDateArrive').prop('required', false);

                    $('#divTravelLoc').hide();

                    $('.localTravelOptions :checkbox').removeAttr('required');
                }
            }).trigger('change');

            $('#placevisited1').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal1').show();

                    $('#locName1').prop('required', true);
                    $('#locAddress1').prop('required', true);
                    $('#locDateFrom1').prop('required', true);
                    $('#locDateTo1').prop('required', true);
                    $('#locWithOngoingCovid1').prop('required', true);
                }
                else {
                    $('#divLocal1').hide();

                    $('#locName1').prop('required', false);
                    $('#locAddress1').prop('required', false);
                    $('#locDateFrom1').prop('required', false);
                    $('#locDateTo1').prop('required', false);
                    $('#locWithOngoingCovid1').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited2').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal2').show();

                    $('#locName2').prop('required', true);
                    $('#locAddress2').prop('required', true);
                    $('#locDateFrom2').prop('required', true);
                    $('#locDateTo2').prop('required', true);
                    $('#locWithOngoingCovid2').prop('required', true);
                }
                else {
                    $('#divLocal2').hide();

                    $('#locName2').prop('required', false);
                    $('#locAddress2').prop('required', false);
                    $('#locDateFrom2').prop('required', false);
                    $('#locDateTo2').prop('required', false);
                    $('#locWithOngoingCovid2').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited3').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal3').show();

                    $('#locName3').prop('required', true);
                    $('#locAddress3').prop('required', true);
                    $('#locDateFrom3').prop('required', true);
                    $('#locDateTo3').prop('required', true);
                    $('#locWithOngoingCovid3').prop('required', true);
                }
                else {
                    $('#divLocal3').hide();

                    $('#locName3').prop('required', false);
                    $('#locAddress3').prop('required', false);
                    $('#locDateFrom3').prop('required', false);
                    $('#locDateTo3').prop('required', false);
                    $('#locWithOngoingCovid3').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited4').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal4').show();

                    $('#locName4').prop('required', true);
                    $('#locAddress4').prop('required', true);
                    $('#locDateFrom4').prop('required', true);
                    $('#locDateTo4').prop('required', true);
                    $('#locWithOngoingCovid4').prop('required', true);
                }
                else {
                    $('#divLocal4').hide();

                    $('#locName4').prop('required', false);
                    $('#locAddress4').prop('required', false);
                    $('#locDateFrom4').prop('required', false);
                    $('#locDateTo4').prop('required', false);
                    $('#locWithOngoingCovid4').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited5').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal5').show();

                    $('#locName5').prop('required', true);
                    $('#locAddress5').prop('required', true);
                    $('#locDateFrom5').prop('required', true);
                    $('#locDateTo5').prop('required', true);
                    $('#locWithOngoingCovid5').prop('required', true);
                }
                else {
                    $('#divLocal5').hide();

                    $('#locName5').prop('required', false);
                    $('#locAddress5').prop('required', false);
                    $('#locDateFrom5').prop('required', false);
                    $('#locDateTo5').prop('required', false);
                    $('#locWithOngoingCovid5').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited6').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal6').show();

                    $('#locName6').prop('required', true);
                    $('#locAddress6').prop('required', true);
                    $('#locDateFrom6').prop('required', true);
                    $('#locDateTo6').prop('required', true);
                    $('#locWithOngoingCovid6').prop('required', true);
                }
                else {
                    $('#divLocal6').hide();

                    $('#locName6').prop('required', false);
                    $('#locAddress6').prop('required', false);
                    $('#locDateFrom6').prop('required', false);
                    $('#locDateTo6').prop('required', false);
                    $('#locWithOngoingCovid6').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited7').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal7').show();

                    $('#locName7').prop('required', true);
                    $('#locAddress7').prop('required', true);
                    $('#locDateFrom7').prop('required', true);
                    $('#locDateTo7').prop('required', true);
                    $('#locWithOngoingCovid7').prop('required', true);
                }
                else {
                    $('#divLocal7').hide();

                    $('#locName7').prop('required', false);
                    $('#locAddress7').prop('required', false);
                    $('#locDateFrom7').prop('required', false);
                    $('#locDateTo7').prop('required', false);
                    $('#locWithOngoingCovid7').prop('required', false);
                }
            }).trigger('change');

            $('#placevisited8').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divLocal8').show();

                    //baguhin kapag kailangan kapag naka-check
                    $('#localVessel1').prop('required', false);
                    $('#localVesselNo1').prop('required', false);
                    $('#localOrigin1').prop('required', false);
                    $('#localDateDepart1').prop('required', false);
                    $('#localDest1').prop('required', false);
                    $('#localDateArrive1').prop('required', false);

                    $('#localVessel2').prop('required', false);
                    $('#localVesselNo2').prop('required', false);
                    $('#localOrigin2').prop('required', false);
                    $('#localDateDepart2').prop('required', false);
                    $('#localDest2').prop('required', false);
                    $('#localDateArrive2').prop('required', false);
                }
                else {
                    $('#divLocal8').hide();

                    $('#localVessel1').prop('required', false);
                    $('#localVesselNo1').prop('required', false);
                    $('#localOrigin1').prop('required', false);
                    $('#localDateDepart1').prop('required', false);
                    $('#localDest1').prop('required', false);
                    $('#localDateArrive1').prop('required', false);

                    $('#localVessel2').prop('required', false);
                    $('#localVesselNo2').prop('required', false);
                    $('#localOrigin2').prop('required', false);
                    $('#localDateDepart2').prop('required', false);
                    $('#localDest2').prop('required', false);
                    $('#localDateArrive2').prop('required', false);

                    $('localVessel1').val("");
                    $('localVesselNo1').val("");
                    $('localOrigin1').val("");
                    $('localDateDepart1').val("");
                    $('localDest1').val("");
                    $('localDateArrive1').val("");

                    $('localVessel2').val("");
                    $('localVesselNo2').val("");
                    $('localOrigin2').val("");
                    $('localDateDepart2').val("");
                    $('localDest2').val("");
                    $('localDateArrive2').val("");
                }
            }).trigger('change');

            $('#pType').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "CLOSE CONTACT") {
                    $('#ifCC').show();
                    $('#ccType').prop('required', true);
                }
                else {
                    $('#ifCC').hide();
                    $('#ccType').prop('required', false);
                }
            }).trigger('change');

            $('#ccid_list').select2({
                theme: "bootstrap",
                placeholder: 'Search by Name / Patient ID ...',
                ajax: {
                    url: "{{route('forms.ajaxcclist')}}?self_id={{$records->id}}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection