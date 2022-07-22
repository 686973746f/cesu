
@extends('layouts.app')

@section('content')
    <div class="container">
        @if($records->outcomeCondition == 'Recovered' && $records->ifOldCif() == false || $records->caseClassification == 'Non-COVID-19 Case' && $records->ifOldCif() == false)
            <form action="{{route('forms.reswab', ['id' => $records->records->id])}}" method="POST">
                @csrf
                <div class="text-right">
                    <button type="submit" class="btn btn-success mb-3" onclick="return confirm('Ang CIF ng Pasyente ay nai-report noong {{date('m/d/Y', strtotime($records->dateReported))}}, ito ay nakaraang {{Carbon\Carbon::parse($records->dateReported)->diffInDays()}} na araw na nakakalipas. Kung nais pa ring magpatuloy sa reswab, I-click ang [OK] button.')"><i class="far fa-plus-square mr-2"></i>Create New CIF / Reswab</button>
                </div>
            </form>
        @endif
        @if($records->ifCaseFinished() && $records->ifOldCif() == false)
            <div class="alert alert-info" role="alert">
                <h5 class="alert-heading font-weight-bold text-danger">Notice:</h5>
                @if($records->outcomeCondition == 'Recovered')
                <p>This CIF of Patient was already marked as <u><strong>RECOVERED</strong></u>.</p>
                <p>Only an admin can update the details of this record to preserve the details of the case.</p>
                <hr>
                <p>If <strong>FOR RESWAB OR REINFECTION</strong>, click the <span class="badge badge-success"><i class="far fa-plus-square mr-2"></i>Create New CIF / Reswab</span> Button above.</p>
                @elseif($records->caseClassification == 'Non-COVID-19 Case')
                <p>This CIF of Patient was already marked as <u><strong>NEGATIVE RESULT</strong></u></p>
                <p>Only an admin can update the details of this record to preserve the details of the case.</p>
                <hr>
                <p>If <strong>FOR RESWAB</strong>, click the <span class="badge badge-success"><i class="far fa-plus-square mr-2"></i>Create New CIF / Reswab</span> Button above.</p>
                @elseif($records->outcomeCondition == 'Died')
                <p>The patient was already declared <u><strong>Dead</strong></u> on {{date('m/d/Y', strtotime($records->outcomeDeathDate))}}. Editing or Creating New CIF for the patient is now disabled.</p>
                <p>You may contact CESU Staff/Encoders if you would like to update information or if you think there was a mistake.</p>
                @endif
                @if($records->outcomeCondition == 'Recovered' || $records->caseClassification == 'Non-COVID-19 Case')
                <hr>
                <p>Other Options:</p>
                @if($records->is_disobedient == 1)
                <div class="alert alert-danger" role="alert">
                    <p>The patient cannot be able to process Medical Certifate as it was marked as <strong class="text-danger">DISOBEDIENT/UNCOOPERATIVE</strong></p>
                    <p><strong>Reason:</strong> {{$records->disobedient_remarks}}</p>
                    <p>Kindly Coordinate with CESU Head for more details.</p>
                </div>
                @else
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#medcertmodal">Generate Medical Certificate / Recovered Form</button>
                @endif
                @endif
            </div>
            @if($records->is_disobedient != 1)
            <form action="{{route('generate_medcert', ['form_id' => $records->id])}}" method="POST">
                @csrf
                <div class="modal fade" id="medcertmodal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Generate Medical Certificate / Recovered Form</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="qDateStart">Quarantine Date Start</label>
                                          <input type="date" class="form-control" name="qDateStart" id="qDateStart" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="qDateEnd">Quarantine Date End</label>
                                            <input type="date" class="form-control" name="qDateEnd" id="qDateEnd" required>
                                          </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label for="purpose">Purpose</label>
                                  <select class="form-control" name="purpose" id="purpose" required>
                                      <option value="Fit to Travel">Fit to Travel</option>
                                      <option value="Fit to Work">Fit to Work</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="whonote">Nurse/Midwife to Note</label>
                                  <select class="form-control" name="whonote" id="whonote">
                                      <option value="1">Based on Name of Interviewer</option>
                                      <option value="2">Other</option>
                                  </select>
                                </div>
                                <div id="whonotediv" class="d-none">
                                    <div class="form-group">
                                        <label for="whonote_other">Name of Nurse/Midwife to Note</label>
                                        <input type="text" class="form-control" name="whonote_other" id="whonote_other">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" value="medcert1" name="submit">Generate Medical Certificate</button>
                                <button type="submit" class="btn btn-primary" value="medcert2" name="submit">Generate Recovered Form</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $('#whonote').change(function (e) { 
                        e.preventDefault();
                        if($(this).val() == 1) {
                            $('#whonotediv').addClass('d-none');
                            $('#whonote_other').prop('required', false);
                        }
                        else {
                            $('#whonotediv').removeClass('d-none');
                            $('#whonote_other').prop('required', true);
                        }
                    });
                </script>
            </form>
            @endif
        @endif
        @if($records->ifOldCif())
        <div class="alert alert-info" role="alert">
            <h5 class="alert-heading font-weight-bold text-danger">Notice:</h5>
            <p>This is an <strong>OLD CIF Data</strong> of the patient. Only an admin can edit the details of this Patient's Old CIF.</p>
            <p>To view the latest CIF details associated with the patient, click <a href="{{route('forms.edit', ['form' => $records->getNewCif()])}}">HERE</a></p>
        </div>
        @else
            @if($records->getOldCif()->count() > 0)
            <div id="accordianId" role="tablist" aria-multiselectable="true">
                <div class="card mb-3">
                    <div class="card-header" role="tab" id="oldcifheader">
                        <a data-toggle="collapse" data-parent="#accordianId" href="#oldcifcontent" aria-expanded="true" aria-controls="oldcifcontent">
                            <i class="fa fa-history mr-2" aria-hidden="true"></i>Previous CIF Record/s of {{$records->records->getName()}}
                        </a>
                    </div>
                    <div id="oldcifcontent" class="collapse in" role="tabpanel" aria-labelledby="oldcifheader">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-center thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>MM</th>
                                            <th>Date Reported</th>
                                            <th>Date Encoded</th>
                                            <th>Health Status</th>
                                            <th>Classification</th>
                                            <th>Outcome</th>
                                            <th>Date Swabbed / Type</th>
                                            <th>Result</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach($records->getOldCif() as $olditem)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{date('m/d/Y', strtotime($olditem->morbidityMonth))}}</td>
                                            <td>{{date('m/d/Y', strtotime($olditem->dateReported))}}</td>
                                            <td>{{date('m/d/Y', strtotime($olditem->created_at))}}</td>
                                            <td>{{$olditem->healthStatus}}</td>
                                            <td>{{$olditem->caseClassification}}</td>
                                            <td>{{$olditem->outcomeCondition}}</td>
                                            <td>{{$olditem->getLatestTestDate()}} / {{$olditem->getLatestTestType()}}</td>
                                            <td>{{$olditem->getLatestTestResult()}}</td>
                                            <td><a href="{{route('forms.edit', ['form' => $olditem->id])}}">View</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
        @if(auth()->user()->ifTopAdmin())
        <form action="/forms/{{$records->id}}" method="POST">
            @csrf
            @method('delete')
            <div class="text-right mb-3">
                <button type="submit" class="btn btn-danger" onclick="return confirm('You will delete this CIF Associated with the Patient. Click OK to Confirm.')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete CIF</button>
            </div>
        </form>
        @endif
        <form action="/forms/{{$records->id}}{{(request()->get('fromView') && request()->get('sdate') && request()->get('edate')) ? "?fromView=".request()->get('fromView')."&sdate=".request()->get('sdate')."&edate=".request()->get('edate')."" : ''}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>eCIF (version 9) - Edit</div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appendix"><i class="fa fa-file mr-2" aria-hidden="true"></i>Appendix</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($msheet)
                    <a href="{{route('msheet.view', ['id' => $msheet->id])}}" class="btn btn-primary btn-block mb-3">View Monitoring Sheet</a>
                    @else
                    <button type="button" onclick="event.preventDefault(); document.getElementById('msheetform').submit();" class="btn btn-success btn-block mb-3"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Create Monitoring Sheet</button>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected while updating the CIF of the Patient:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <p>1.) The Case Investigation Form (CIF) is meant to be administered as an interview by a health care worker or any personnel of the DRU. <b>This is not a self-administered questionnaire.</b></p>
                        <p>2.) Please be advised that DRUs are only allowed to obtain <b>1 copy of accomplished CIF</b> from a patient.</p>
                        <p>3.) Please fill out all blanks and put a check mark on the appropriate box. <b>Items with asterisk mark <span class="text-danger">(*)</span> are required fields.</b></p>
                    </div>
                    <hr>
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgType')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <label for=""><span class="text-danger font-weight-bold">*</span>Selected CIF Information to Edit</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="#{{$records->records->id}} - {{$records->records->lname}}, {{$records->records->fname}} {{$records->records->mname}} | {{$records->records->getAge()}} / {{substr($records->records->gender,0,1)}} | {{date("m/d/Y", strtotime($records->records->bdate))}}" disabled>
                        <div class="input-group-append">
                            <a class="btn btn-outline-primary" id="quickreclink" href="/records/{{$records->records_id}}/edit?fromFormsPage=true">Edit Record</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="bg-light">CIF ID</td>
                                    <td class="text-center">{{$records->id}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light">Encoded By / Date</td>
                                    <td class="text-center">{{$records->user->name}} ({{date("m/d/Y h:i A - l", strtotime($records->created_at))}})</td>
                                </tr>
                                @if(!is_null($records->updated_by))
                                <tr>
                                    <td class="bg-light">Edited By / Date</td>
                                    <td class="text-center">{{$records->getEditedBy()}} ({{date("m/d/Y h:i A - l", strtotime($records->updated_at))}})</td>
                                </tr>
                                @endif
                                @if($records->getReferralCode() != 'N/A')
                                <tr>
                                    <td class="bg-light">Pa-swab Schedule Code</td>
                                    <td class="text-center">{{$records->majikCode}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light">Pa-swab Referral Code</td>
                                    <td class="text-center">{{$records->getReferralCode()}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks <small><i>(If Applicable)</i></small></label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $records->remarks)}}</textarea>
                    </div>
                    @if(auth()->user()->ifTopAdmin() || $records->is_disobedient != 1)
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="is_disobedient" id="is_disobedient" value="1" {{($records->is_disobedient == 1) ? 'checked' : ''}}>Is Patient Disobedient/Uncooperative?</label>
                        </div>
                        <div class="form-group mt-2 d-none" id="disobedient_div">
                            <label for="disobedient_remarks"><span class="text-danger font-weight-bold">*</span>Disobedient Remarks</label>
                            <textarea class="form-control" name="disobedient_remarks" id="disobedient_remarks" rows="3">{{$records->disobedient_remarks}}</textarea>
                        </div>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="morbidityMonth"><span class="text-danger font-weight-bold">*</span>Morbidity Month [MM] <i>(Kung kailan na-encode)</i></label>
                              <input type="date" class="form-control" id="morbidityMonth" name="morbidityMonth" min="2020-01-01" value="{{old('morbidityMonth', $records->morbidityMonth)}}" max="{{($is_cutoff) ? date('Y-m-d', strtotime('+1 Day')) : date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for=""><span class="text-danger font-weight-bold">*</span>Morbidity Week [MW]</label>
                              <input type="text" class="form-control" value="{{!is_null(old('morbidityMonth')) ? date('W', strtotime(old('morbidityMonth'))) : date('W', strtotime($records->morbidityMonth))}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dateReported"><span class="text-danger font-weight-bold">*</span>Date Reported <i>(Kung kailan lumabas ang Swab Test Result)</i></label>
                        <input type="date" class="form-control" name="dateReported" id="dateReported" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{old('dateReported', date('Y-m-d', strtotime($records->dateReported)))}}" required>
                        <small class="text-muted">Note: For Positive/Negative Result, it will be automatically changed based on Date Released of Swab Result <i>(Under 2.7 Laboratory Information)</i>.</small>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="drunit"><span class="text-danger font-weight-bold">*</span>Disease Reporting Unit (DRU)</label>
                                <input type="text" class="form-control" name="drunit" id="drunit" value="{{old('drunit', $records->drunit)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drregion"><span class="text-danger font-weight-bold">*</span>DRU Region</label>
                                        <input type="text" class="form-control" name="drregion" id="drregion" value="{{old('drregion', $records->drregion)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drprovince"><span class="text-danger font-weight-bold">*</span>DRU Province</label>
                                        <input type="text" class="form-control" name="drprovince" id="drprovince" value="{{old('drprovince', $records->drprovince)}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><span class="text-danger font-weight-bold">*</span>Philhealth No.</label>
                                <input type="text" name="" id="" class="form-control" value="{{(is_null($records->records->philhealth)) ? 'N/A' : $records->records->philhealth}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if(!$records->user->isCesuAccount() && !auth()->user()->isCesuAccount())
                            <div class="form-group">
                                <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                                <input type="text" name="interviewerName" id="interviewerName" class="form-control" value="{{old('interviewerName', $records->interviewerName)}}" readonly required>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                              <select name="interviewerName" id="interviewerName" required>
                                <option value="" disabled {{(empty(old('interviewerName', $records->interviewerName))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach($interviewers as $key => $interviewer)
                                  <option value="{{$interviewer->lname.", ".$interviewer->fname}}" {{(old('interviewerName', $records->interviewerName) == $interviewer->lname.", ".$interviewer->fname || $records->user->defaultInterviewer() == $interviewer->lname.", ".$interviewer->fname) ? 'selected' : ''}}>{{$interviewer->lname.", ".$interviewer->fname." ".$interviewer->mname}}{{(!is_null($interviewer->brgy_id)) ? " (".$interviewer->brgy->brgyName.")" : ''}}{{(!is_null($interviewer->desc)) ? " - ".$interviewer->desc : ""}}</option>
                                  @endforeach
                              </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewerMobile"><span class="text-danger font-weight-bold">*</span>Contact Number of Interviewer</label>
                                <input type="number" name="interviewerMobile" id="interviewerMobile" class="form-control" value="{{old('interviewerMobile', $records->interviewerMobile)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date of Interview</label>
                                <input type="date" name="interviewDate" id="interviewDate" class="form-control" value="{{old('interviewDate', $records->interviewDate)}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantName">Name of Informant <small><i>(If patient unavailable)</i></small></label>
                                <input type="text" name="informantName" id="informantName" class="form-control" value="{{old('informantName', $records->informantName)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantRelationship">Relationship</label>
                                <select class="form-control" name="informantRelationship" id="informantRelationship">
                                <option value="" disabled {{(is_null(old('informantRelationship', $records->informantRelationship))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Relative" {{(old('informantRelationship', $records->informantRelationship) == "RELATIVE") ? 'selected' : ''}}>Family/Relative</option>
                                <option value="Friend" {{(old('informantRelationship', $records->informantRelationship) == "Friend") ? 'selected' : ''}}>Friend</option>
                                <option value="Others" {{(old('informantRelationship', $records->informantRelationship) == "Others") ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantMobile">Contact Number of Informant</label>
                                <input type="number" name="informantMobile" id="informantMobile" class="form-control" value="{{old('informantMobile', $records->informantMobile)}}">
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
                                        <input class="form-check-input" type="checkbox" value="1" id="" name="existingCaseList[]" required {{(in_array("1", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Not applicable (New case)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="2" id="" name="existingCaseList[]" required {{(in_array("2", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Not applicable (Unknown)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="3" id="" name="existingCaseList[]" required {{(in_array("3", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update symptoms
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="4" id="" name="existingCaseList[]" required {{(in_array("4", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update health status / outcome
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="5" id="" name="existingCaseList[]" required {{(in_array("5", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update case classification
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="6" id="" name="existingCaseList[]" required {{(in_array("6", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update vaccination
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="7" id="" name="existingCaseList[]" required {{(in_array("7", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update lab result
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="8" id="" name="existingCaseList[]" required {{(in_array("8", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update chest imaging findings
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="9" id="" name="existingCaseList[]" required {{(in_array("9", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update disposition
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="10" id="" name="existingCaseList[]" required {{(in_array("10", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Update exposure / travel history
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="11" id="ecothers" name="existingCaseList[]" required {{(in_array("11", old('existingCaseList', explode(",", $records->existingCaseList)))) ? 'checked' : ''}}>
                                        <label class="form-check-label" for="">
                                            Others
                                        </label>
                                    </div>
                                    <div id="divECOthers">
                                        <div class="form-group mt-2">
                                            <label for="ecOthersRemarks"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                          <input type="text" name="ecOthersRemarks" id="ecOthersRemarks" value="{{old('ecOthersRemarks', $records->ecOthersRemarks)}}" class="form-control" style="text-transform: uppercase;">
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
                                <option value="PROBABLE" @if(old('pType', $records->pType) == "PROBABLE"){{'selected'}}@endif>COVID-19 Case (Suspect, Probable, or Confirmed)</option>
                                <option value="CLOSE CONTACT" @if(old('pType', $records->pType) == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                <option value="TESTING" @if(old('pType', $records->pType) == "TESTING"){{'selected'}}@endif>For RT-PCR Testing (Not a Case of Close Contact)</option>
                                </select>
                            </div>
                            <div id="ifCC">
                                <div class="form-group">
                                  <label for="ccType"><span class="text-danger font-weight-bold">*</span>Close Contact Type</label>
                                  <select class="form-control" name="ccType" id="ccType">
                                    <option value="1" {{(old('ccType', $records->ccType) == 1) ? 'selected' : ''}}>Primary (1st Generation)</option>
                                    <option value="2" {{(old('ccType', $records->ccType) == 2) ? 'selected' : ''}}>Secondary (2nd Generation)</option>
                                    <option value="3" {{(old('ccType', $records->ccType) == 3) ? 'selected' : ''}}>Tertiary (3rd Generation)</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="isForHospitalization"><span class="text-danger font-weight-bold">*</span>For Hospitalization</label>
                                <select class="form-control" name="isForHospitalization" id="isForHospitalization" required>
                                  <option value="1" {{(old('isForHospitalization', $records->isForHospitalization) == 1) ? 'selected' : ''}}>Yes</option>
                                  <option value="0" {{(old('isForHospitalization', $records->isForHospitalization) == 0) ? 'selected' : ''}} >No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="testingCat"><span class="text-danger font-weight-bold">*</span>Testing Category/Subgroup <i>(Select all that apply)</i></label>
                      <select class="form-control" name="testingCat[]" id="testingCat" required multiple>
                        <option value="A" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('A')) ? 'selected' : ''}}>A. With Severe/Critical Symptoms</option>
                        <option value="B" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('B')) ? 'selected' : ''}}>B. With Mild Symptoms (Senior Citizens / Patients w. Comorbidity)</option>
                        <option value="C" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('C')) ? 'selected' : ''}}>C. With Mild Symptoms Only</option>
                        <optgroup label="Category D - No Symptoms but with Relevant History of Travel or Contact">
                            <option value="D.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('D.1')) ? 'selected' : ''}}>D.1 Contact Traced Individuals</option>
                            <option value="D.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('D.2')) ? 'selected' : ''}}>D.2 Health Care Workers</option>
                            <option value="D.3" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('D.3')) ? 'selected' : ''}}>D.3 Returning Overseas Filipino</option>
                            <option value="D.4" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('D.4')) ? 'selected' : ''}}>D.4 Locally Stranded Individuals (LSI)</option>
                        </optgroup>
                        <optgroup label="Category E - Frontliners Indirectly Involved in Healthcare Provision">
                            <optgroup label="E1 - High Direct Exposure to COVID-19 Regardless of Location">
                                <option value="E1.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E1.1")) ? 'selected' : ''}}>E1.1 Quarantine Facilities</option>
                                <option value="E1.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E1.2")) ? 'selected' : ''}}>E1.2 COVID-19 Swabbing Center</option>
                                <option value="E1.3" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E1.3")) ? 'selected' : ''}}>E1.3 Contact Tracing</option>
                                <option value="E1.4" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E1.4")) ? 'selected' : ''}}>E1.4 Personnel Conducting Swabbing</option>
                            </optgroup>
                            <optgroup label="E2 - Not High or Indirect Exposure to COVID-19">
                                <option value="E2.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.1")) ? 'selected' : ''}}>E2.1 Quarantine Control Points (eg. AFP, BFP, etc.)</option>
                                <option value="E2.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.2")) ? 'selected' : ''}}>E2.2 National/regional/local risk of reduction management</option>
                                <option value="E2.3" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.3")) ? 'selected' : ''}}>E2.3 Government Employees</option>
                                <option value="E2.4" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.4")) ? 'selected' : ''}}>E2.4 BHERTs</option>
                                <option value="E2.5" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.5")) ? 'selected' : ''}}>E2.5 Bureau of Corrections & Bureau of Jail Penology and Management</option>
                                <option value="E2.6" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.6")) ? 'selected' : ''}}>E2.6 One-Stop-Shop in the Management of the Returning Overseas Filipinos</option>
                                <option value="E2.7" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.7")) ? 'selected' : ''}}>E2.7 Border Control or Patrol Officer (eg. Coast Guard)</option>
                                <option value="E2.8" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("E2.8")) ? 'selected' : ''}}>E2.8 Social Workers</option>
                            </optgroup>
                        </optgroup>
                        <optgroup label="Category F - Other vulnerable patients and those living in confined spaces">
                            <option value="F.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.1")) ? 'selected' : ''}}>F.1 Pregnant Patients</option>
                            <option value="F.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.2")) ? 'selected' : ''}}>F.2 Dialysis Patients</option>
                            <option value="F.3" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.3")) ? 'selected' : ''}}>F.3 Immunocompromised (HIV/AIDS)</option>
                            <option value="F.4" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.4")) ? 'selected' : ''}}>F.4 Chemo and radiotherapy patient</option>
                            <option value="F.5" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.5")) ? 'selected' : ''}}>F.5 Elective surgical procedures with high risk transmission</option>
                            <option value="F.6" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.6")) ? 'selected' : ''}}>F.6 Organ/Bone Marrow/Stem Cell Transplant</option>
                            <option value="F.7" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains("F.7")) ? 'selected' : ''}}>F.7 Persons in Jail and Penitentiaries</option>
                        </optgroup>
                        <option value="F" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('F')) ? 'selected' : ''}}>F. Other Vulnerable Patients and Living in Confined Spaces (e.g. Pregnant, Dialysis Patient, HIV/AIDS, Chemotherapy, For Operation, Jail Admission)</option>
                        <option value="G" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('G')) ? 'selected' : ''}}>G. Residents, occupants, or workes in a localized area with an active COVID-19 cluster</option>
                        <optgroup label="Category H - Frontliners in Tourist Zones">
                            <option value="H.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('H.1')) ? 'selected' : ''}}>H.1 Workers/Employees in the Hospitality and Tourism Sectors</option>
                            <option value="H.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('H.2')) ? 'selected' : ''}}>H.2 Travelers</option>
                        </optgroup>
                        <option value="I" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('I')) ? 'selected' : ''}}>I. Employees of Manufacturing Companies and Public Service Providers Registered in Economic Zones</option>
                        <optgroup label="Category J - Economy Workers">
                            <option value="J1.1" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.1')) ? 'selected' : ''}}>J1.1 Transport and Logistics</option>
                            <option value="J1.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.2')) ? 'selected' : ''}}>J1.2 Food Retails</option>
                            <option value="J1.3" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.3')) ? 'selected' : ''}}>J1.3 Education</option>
                            <option value="J1.4" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.4')) ? 'selected' : ''}}>J1.4 Financial Services</option>
                            <option value="J1.5" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.5')) ? 'selected' : ''}}>J1.5 Non-food Retail</option>
                            <option value="J1.6" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.6')) ? 'selected' : ''}}>J1.6 Services <small>(Hairdressers, manicurist, embalmers, security guards, messengers, massage therapists, etc.)</small></option>
                            <option value="J1.7" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.7')) ? 'selected' : ''}}>J1.7 Market Vendors</option>
                            <option value="J1.8" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.8')) ? 'selected' : ''}}>J1.8 Construction</option>
                            <option value="J1.9" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.9')) ? 'selected' : ''}}>J1.9 Water Supply, Sewerage, Waste Management</option>
                            <option value="J1.10" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.10')) ? 'selected' : ''}}>J1.10 Public Sector</option>
                            <option value="J1.11" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J1.11')) ? 'selected' : ''}}>J1.11 Mass Media</option>
                            <!--<option value="J.2" {{(collect(old('testingCat', explode(',', $records->testingCat)))->contains('J.2')) ? 'selected' : ''}}>J.2 Other Employee not Covered in J.1 Category but required to undergo testing every quarter</option>-->
                        </optgroup>
                        <option></option>
                      </select>
                      <small class="text-muted">Refer to Appendix 2 for more details (Button in top-right corner of this page)</small>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 1. Patient Information</div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">1.1 Patient Profile</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Last Name</label>
                                                <input type="text" class="form-control" value="{{$records->records->lname}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">First Name</label>
                                                <input type="text" class="form-control" value="{{$records->records->fname}}" id="" disabled>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Middle Name</label>
                                                <input type="text" class="form-control" value="{{$records->records->mname}}" id="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Birthdate (MM/DD/YYYY)</label>
                                                <input type="text" class="form-control" value="{{date('m/d/Y', strtotime($records->records->bdate))}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Age</label>
                                                <input type="text" class="form-control" value="{{$records->records->getAge($records->records->bdate)}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Gender</label>
                                                <input type="text" class="form-control" value="{{$records->records->gender}}" id="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Civil Status</label>
                                                <input type="text" class="form-control" value="{{$records->records->cs}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Nationality</label>
                                                <input type="text" class="form-control" value="{{$records->records->nationality}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Occupation</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation)) ? 'N/A' : $records->records->occupation}}" id="" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Works in a Closed Setting</label>
                                                <input type="text" class="form-control" value="{{$records->records->worksInClosedSetting}}" disabled>
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
                                                <input type="text" class="form-control" value="{{$records->records->address_houseno}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street/Subdivision/Purok/Sitio</label>
                                                <input type="text" class="form-control" value="{{$records->records->address_street}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" value="{{$records->records->address_brgy}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" value="{{$records->records->address_city}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" value="{{$records->records->address_province}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Home Phone No. (& Area Code)</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->phoneno)) ? 'N/A' : $records->records->phoneno}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cellphone No.</label>
                                                <input type="text" class="form-control" value="{{$records->records->mobile}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->email)) ? 'N/A' : $records->records->email}}" disabled>
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
                                                <input type="text" class="form-control" value="{{(is_null($records->permaaddress_houseno)) ? "N/A" : $records->records->permaaddress_houseno}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street/Subdivision/Purok/Sitio</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaaddress_street)) ? "N/A" : $records->records->permaaddress_street}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaaddress_brgy)) ? "N/A" : $records->records->permaaddress_brgy}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaaddress_city)) ? "N/A" : $records->records->permaaddress_city}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaaddress_province)) ? "N/A" : $records->records->permaaddress_province}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Home Phone No. (& Area Code)</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaphoneno)) ? "N/A" : $records->records->permaphoneno}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cellphone No.</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permamobile)) ? "N/A" : $records->records->permamobile}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->permaemail)) ? "N/A" : $records->records->permaemail}}" disabled>
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
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_lotbldg)) ? 'N/A' : $records->records->occupation_lotbldg}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street/Zone</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_street)) ? 'N/A' : $records->records->occupation_street}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_brgy)) ? 'N/A' : $records->records->occupation_brgy}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_city)) ? 'N/A' : $records->records->occupation_city}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_province)) ? 'N/A' : $records->records->occupation_province}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Name of Workplace</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_name)) ? 'N/A' : $records->records->occupation_name}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Phone No./Cellphone No.</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_mobile)) ? 'N/A' : $records->records->occupation_mobile}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" value="{{(is_null($records->records->occupation_email)) ? 'N/A' : $records->records->occupation_email}}" disabled>
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
                                                    <option value="1" {{(old('isHealthCareWorker', $records->isHealthCareWorker) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isHealthCareWorker', $records->isHealthCareWorker) == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisHealthCareWorker">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="healthCareCompanyName"><span class="text-danger font-weight-bold">*</span>Name of Health Facility</label>
                                                            <input type="text" class="form-control" name="healthCareCompanyName" id="healthCareCompanyName" value="{{old('healthCareCompanyName', $records->healthCareCompanyName)}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="healthCareCompanyLocation"><span class="text-danger font-weight-bold">*</span>Location</label>
                                                            <input type="text" class="form-control" name="healthCareCompanyLocation" id="healthCareCompanyLocation" value="{{old('healthCareCompanyLocation', $records->healthCareCompanyLocation)}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="isOFW"><span class="text-danger font-weight-bold">*</span>Returning Overseas Filipino</label>
                                                <select class="form-control" name="isOFW" id="isOFW" required>
                                                    <option value="1" {{(old('isOFW', $records->isOFW) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isOFW', $records->isOFW) == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisOFW">
                                                <div class="form-group">
                                                    <label for="OFWCountyOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                                    <select class="form-control" name="OFWCountyOfOrigin" id="OFWCountyOfOrigin">
                                                        <option value="" disabled {{(is_null(old('OFWCountyOfOrigin', $records->OFWCountyOfOrigin))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach ($countries as $country)
                                                            @if($country != 'Philippines')
                                                                <option value="{{$country}}" {{(old('OFWCountyOfOrigin', $records->OFWCountyOfOrigin) == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="OFWPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                                    <input type="text" class="form-control" name="OFWPassportNo" id="OFWPassportNo" value="{{old('OFWPassportNo', $records->OFWPassportNo)}}" style="text-transform: uppercase;">
                                                </div>
                                                <div class="form-group">
                                                  <label for="ofwType"><span class="text-danger font-weight-bold">*</span>OFW?</label>
                                                  <select class="form-control" name="ofwType" id="ofwType">
                                                    <option value="1" {{(old('ofwType', $records->ofwType) == "YES") ? 'selected' : ''}}>Yes</option>
                                                    <option value="2" {{(old('ofwType', $records->ofwType) == "NO") ? 'selected' : ''}}>No (Non-OFW)</option>
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
                                                    <option value="1" {{(old('isFNT', $records->isFNT) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isFNT', $records->isFNT) == 0 || is_null(old('isFNT', $records->isFNT))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisFNT">
                                                <div class="form-group">
                                                    <label for="FNTCountryOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                                    <select class="form-control" name="FNTCountryOfOrigin" id="FNTCountryOfOrigin">
                                                        <option value="" disabled {{(is_null(old('FNTCountryOfOrigin', $records->FNTCountryOfOrigin))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach ($countries as $country)
                                                            @if($country != 'Philippines')
                                                                <option value="{{$country}}" {{(old('FNTCountryOfOrigin', $records->FNTCountryOfOrigin) == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="FNTPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                                    <input type="text" class="form-control" name="FNTPassportNo" id="FNTPassportNo" value="{{old('FNTPassportNo', $records->FNTPassportNo)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isLSI"><span class="text-danger font-weight-bold">*</span>Locally Stranded Individual/APOR/Traveler</label>
                                                <select class="form-control" name="isLSI" id="isLSI" required>
                                                    <option value="1" {{(old('isLSI', $records->isLSI) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isLSI', $records->isLSI) == 0 || is_null(old('isLSI', $records->isLSI))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisLSI">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="LSIProvince"><span class="text-danger font-weight-bold">*</span>Province of Origin</label>
                                                          <select class="form-control" name="LSIProvince" id="LSIProvince">
                                                                <option value="" disabled {{(is_null(old('LSIProvince', $records->LSIProvince))) ? 'selected' : ''}}>Choose...</option>
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="LSICity"><span class="text-danger font-weight-bold">*</span>City of Origin</label>
                                                            <select class="form-control" name="LSICity" id="LSICity">
                                                                  <option value="" disabled {{(is_null(old('LSICity', $records->LSICity))) ? 'selected' : ''}}>Choose...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label for="lsiType"><span class="text-danger font-weight-bold">*</span>Type</label>
                                                  <select class="form-control" name="lsiType" id="lsiType">
                                                    <option value="1" {{(old('lsiType', $records->lsiType) == 1) ? 'selected' : ''}}>Locally Stranted Individual</option>
                                                    <option value="0" {{(old('lsiType', $records->lsiType) == 2) ? 'selected' : ''}}>Authorized Person Outside Residence/Local Traveler</option>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="isLivesOnClosedSettings"><span class="text-danger font-weight-bold">*</span>Lives in Closed Settings</label>
                                                <select class="form-control" name="isLivesOnClosedSettings" id="isLivesOnClosedSettings" required>
                                                    <option value="1" {{(old('isLivesOnClosedSettings', $records->isLivesOnClosedSettings) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isLivesOnClosedSettings', $records->isLivesOnClosedSettings) == 0 || is_null(old('isLivesOnClosedSettings', $records->isLivesOnClosedSettings))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisLivesOnClosedSettings">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="institutionType"><span class="text-danger font-weight-bold">*</span>Specify Institution Type</label>
                                                          <input type="text" class="form-control" name="institutionType" id="institutionType" value="{{old('institutionType', $records->institutionType)}}" style="text-transform: uppercase;">
                                                          <small><i>(e.g. prisons, residential facilities, retirement communities, care homes, camps etc.)</i></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="institutionName"><span class="text-danger font-weight-bold">*</span>Name of Institution</label>
                                                            <input type="text" class="form-control" name="institutionName" id="institutionName" value="{{old('institutionName', $records->institutionName)}}" style="text-transform: uppercase;">
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
                                                    <option value="1" {{(old('havePreviousCovidConsultation', $records->havePreviousCovidConsultation) == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('havePreviousCovidConsultation', $records->havePreviousCovidConsultation) == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divYes1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="facilityNameOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Name of facility where first consult was done</label>
                                                            <input type="text" class="form-control" name="facilityNameOfFirstConsult" id="facilityNameOfFirstConsult" value="{{old('facilityNameOfFirstConsult', $records->facilityNameOfFirstConsult)}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dateOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Date of First Consult</label>
                                                            <input type="date" class="form-control" name="dateOfFirstConsult" id="dateOfFirstConsult" value="{{old('dateOfFirstConsult', $records->dateOfFirstConsult)}}" max="{{date('Y-m-d')}}">
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
                                                    <option value="1" {{(old('dispositionType', $records->dispoType) == 1) ? 'selected' : ''}}>Admitted in hospital</option>
                                                    <option value="6" {{(old('dispositionType', $records->dispoType) == 6) ? 'selected' : ''}}>Admitted in General Trias Isolation Facility</option>
                                                    <option value="7" {{(old('dispositionType', $records->dispoType) == 7) ? 'selected' : ''}}>Admitted in General Trias Isolation Facility #2 (Eagle Ridge, Brgy. Javalera)</option>
                                                    <option value="2" {{(old('dispositionType', $records->dispoType) == 2) ? 'selected' : ''}}>Admitted in OTHER isolation/quarantine facility</option>
                                                    <option value="3" {{(old('dispositionType', $records->dispoType) == 3) ? 'selected' : ''}}>In home isolation/quarantine</option>
                                                    <option value="4" {{(old('dispositionType', $records->dispoType) == 4) ? 'selected' : ''}}>Discharged to home</option>
                                                    <option value="5" {{(old('dispositionType', $records->dispoType) == 5) ? 'selected' : ''}}>Others</option>
                                                </select>
                                            </div>
                                            <div id="divYes5">
                                                <div class="form-group">
                                                    <label for="dispositionName" id="dispositionlabel"></label>
                                                    <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName', $records->dispoName)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="divYes6">
                                                <div class="form-group">
                                                    <label for="dispositionDate" id="dispositiondatelabel"></label>
                                                    <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate', date('Y-m-d\TH:i', strtotime($records->dispoDate)))}}">
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
                                                    <option value="Asymptomatic" {{(old('healthStatus', $records->healthStatus) == 'Asymptomatic') ? 'selected' : ''}}>Asymptomatic </option>
                                                    <option value="Mild" {{(old('healthStatus', $records->healthStatus) == 'Mild') ? 'selected' : ''}}>Mild</option>
                                                    <option value="Moderate" {{(old('healthStatus', $records->healthStatus) == 'Moderate') ? 'selected' : ''}}>Moderate</option>
                                                    <option value="Severe" {{(old('healthStatus', $records->healthStatus) == 'Severe') ? 'selected' : ''}}>Severe</option>
                                                    <option value="Critical" {{(old('healthStatus', $records->healthStatus) == 'Critical') ? 'selected' : ''}}>Critical</option>
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
                                                    @if($records->caseClassification != 'Confirmed' || auth()->user()->ifTopAdmin())
                                                    <option value="Probable" {{(old('caseClassification', $records->caseClassification) == 'Probable') ? 'selected' : ''}}>Probable</option>
                                                    <option value="Suspect" {{(old('caseClassification', $records->caseClassification) == 'Suspect') ? 'selected' : ''}}>Suspect</option>
                                                    @endif
                                                    <option value="Confirmed" {{(old('caseClassification', $records->caseClassification) == 'Confirmed') ? 'selected' : ''}}>Confirmed (Select if the Result is Positive +)</option>
                                                    @if($records->caseClassification != 'Confirmed' || auth()->user()->ifTopAdmin())
                                                    <option value="Non-COVID-19 Case" {{(old('caseClassification', $records->caseClassification) == 'Non-COVID-19 Case') ? 'selected' : ''}}>Non-COVID-19 Case (Select if the Result is Negative -)</option>
                                                    @endif
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
                                                    <option value="" {{(is_null(old('confirmedVariantName', $records->confirmedVariantName))) ? 'selected' : ''}}>Unspecified</option>
                                                    <option value="ALPHA" {{(old('confirmedVariantName', $records->confirmedVariantName) == 'ALPHA') ? 'selected' : ''}}>ALPHA (B.1.1.7) - GB</option>
                                                    <option value="BETA" {{(old('confirmedVariantName', $records->confirmedVariantName) == 'BETA') ? 'selected' : ''}}>BETA (B.1.351) - ZA</option>
                                                    <option value="DELTA" {{(old('confirmedVariantName', $records->confirmedVariantName) == 'DELTA') ? 'selected' : ''}}>DELTA (B.1.617.2) - IN</option>
                                                    <option value="GAMMA" {{(old('confirmedVariantName', $records->confirmedVariantName) == 'GAMMA') ? 'selected' : ''}}>GAMMA (P.1) - BR</option>
                                                    <option value="OMICRON" {{(old('confirmedVariantName', $records->confirmedVariantName) == 'OMICRON') ? 'selected' : ''}}>OMICRON (B.1.1.529)</option>
                                                  </select>
                                                </div>
                                            </div>
                                            <div id="askIfReinfected">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="reinfected" id="reinfected" value="1" {{(old('reinfected', $records->reinfected) == 1) ? 'checked' : ''}}>
                                                      Case of Re-infection
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">2.5 COVID-19 Vaccination Information</div>
                                <div class="card-body">
                                    @if(!is_null($records->records->vaccinationDate1))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Name of Vaccine</label>
                                                <input type="text" class="form-control" name="" id="" value="{{$records->records->vaccinationName1}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="">1.) First Dose Date</label>
                                              <input type="date" class="form-control" name="" id="" value="{{$records->records->vaccinationDate1}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Vaccination Center/Facility</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->vaccinationFacility1) ? $records->records->vaccinationFacility1 : 'N/A'}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Region of Health Facility</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->vaccinationRegion1) ? $records->records->vaccinationRegion1 : 'N/A'}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Adverse Event/s</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->haveAdverseEvents1 == 1) ? 'YES' : 'NO'}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!is_null($records->records->vaccinationDate2))
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="">2.) Second Dose Date</label>
                                              <input type="date" class="form-control" name="" id="" value="{{$records->records->vaccinationDate2}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Vaccination Center/Facility</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->vaccinationFacility2) ? $records->records->vaccinationFacility2 : 'N/A'}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Region of Health Facility</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->vaccinationRegion2) ? $records->records->vaccinationRegion2 : 'N/A'}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Adverse Event/s</label>
                                                <input type="text" class="form-control" name="" id="" value="{{($records->records->haveAdverseEvents2 == 1) ? 'YES' : 'NO'}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    <p class="text-center">Not yet Vaccinated.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">2.6 Clinical Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="dateOnsetOfIllness">Date of Onset of Illness</label>
                                              <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" max="{{date('Y-m-d')}}" value="{{old('dateOnsetOfIllness', $records->dateOnsetOfIllness)}}">
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
                                                                  {{(in_array("Fever", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck2">Fever</label>
                                                            </div>
                                                            <div id="divFeverChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
                                                                  <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" max="90" step=".1" value="{{old('SASFeverDeg', $records->SASFeverDeg)}}">
                                                                </div>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Cough"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck3"
                                                                  {{(in_array("Cough", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck3">Cough</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Colds"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck19"
                                                                  {{(in_array("Colds", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck19">Colds</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="General Weakness"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck4"
                                                                  {{(in_array("General Weakness", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Fatigue", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Headache", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Myalgia", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck7">Myalgia/Body Pain</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Sore throat"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck8"
                                                                  {{(in_array("Sore throat", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck8">Sore Throat</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Coryza"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck9"
                                                                  {{(in_array("Coryza", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck9">Coryza</label>
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
                                                                  {{(in_array("Dyspnea", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Anorexia", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Nausea", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Vomiting", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Diarrhea", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Altered Mental Status", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Anosmia (Loss of Smell)", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck16">Anosmia/Loss of Smell</small></label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Ageusia (Loss of Taste)"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck17"
                                                                  {{(in_array("Ageusia (Loss of Taste)", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck17">Ageusia/Loss of Taste</small></label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Others"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck18"
                                                                  {{(in_array("Others", old('sasCheck', explode(",", $records->SAS)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck18">Others</label>
                                                            </div>
                                                            <div id="divSASOtherChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASOtherRemarks">Specify Findings <small>(Separate each with commas [,])</small></label>
                                                                  <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks', $records->SASOtherRemarks)}}" style="text-transform: uppercase;">
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
                                                                  {{(in_array("None", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Hypertension", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Diabetes", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Heart Disease", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Lung Disease", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Gastrointestinal", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Genito-urinary", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Neurological Disease", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Cancer", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Dialysis", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Operation", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Transplant", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
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
                                                                  {{(in_array("Others", old('comCheck', explode(",", $records->COMO)))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck10">Others</label>
                                                            </div>
                                                            <div id="divComOthersChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="COMOOtherRemarks">Specify Findings</label>
                                                                  <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks', $records->COMOOtherRemarks)}}" style="text-transform: uppercase;">
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
                                                        <input type="text" class="form-control" value="{{($records->records->isPregnant == 1) ? "Yes" : "No"}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="PregnantLMP"><span class="text-danger font-weight-bold">*</span>Last Menstrual Period (LMP)</label>
                                                        <input type="date" class="form-control" name="PregnantLMP" id="PregnantLMP" value="{{old('PregnantLMP', $records->PregnantLMP)}}" {{($records->records->gender == "FEMALE" && $records->records->isPregnant == 1) ? 'required' : 'disabled'}}>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <label for="highRiskPregnancy"><span class="text-danger font-weight-bold">*</span>High Risk Pregnancy?</label>
                                              <select class="form-control" name="highRiskPregnancy" id="highRiskPregnancy" {{($records->records->gender == "FEMALE" && $records->records->isPregnant == 1) ? 'required' : 'disabled'}}>
                                                <option value="0" {{(old('highRiskPregnancy', $records->PregnantHighRisk) == 0) ? 'selected' : ''}}>No</option>
                                                <option value="1" {{(old('highRiskPregnancy', $records->PregnantHighRisk) == 1) ? 'selected' : ''}}>Yes</option>
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                      <label for="diagWithSARI"><span class="text-danger font-weight-bold">*</span>Was diagnosed to have Severe Acute Respiratory Illness?</label>
                                      <select class="form-control" name="diagWithSARI" id="diagWithSARI" required>
                                        <option value="1" {{(old('diagWithSARI', $records->diagWithSARI) == 1) ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{(is_null(old('diagWithSARI', $records->diagWithSARI)) || old('diagWithSARI', $records->diagWithSARI) == 0) ? 'selected' : ''}}>No</option>
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
                                                      <input type="date" class="form-control" name="imagingDoneDate" id="imagingDoneDate" value="{{old('imagingDoneDate', $records->imagingDoneDate)}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                      <label for="imagingDone">Imaging done</label>
                                                      <select class="form-control" name="imagingDone" id="imagingDone" required>
                                                        <option value="None" {{(old('imagingDone', $records->imagingDone) == "None") ? 'selected' : ''}}>None</option>
                                                        <option value="Chest Radiography" {{(old('imagingDone', $records->imagingDone) == "Chest Radiography") ? 'selected' : ''}}>Chest Radiography</option>
                                                        <option value="Chest CT" {{(old('imagingDone', $records->imagingDone) == "Chest CT") ? 'selected' : ''}}>Chest CT</option>
                                                        <option value="Lung Ultrasound" {{(old('imagingDone', $records->imagingDone) == "Lung Ultrasound") ? 'selected' : ''}}>Lung Ultrasound</option>
                                                      </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                      <label for="imagingResult">Results</label>
                                                      <select class="form-control" name="imagingResult" id="imagingResult" aria-valuemax="">
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
                                                          <input type="text" class="form-control" name="imagingOtherFindings" id="imagingOtherFindings" value="{{old('imagingOtherFindings', $records->imagingOtherFindings)}}">
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
                                                  <option value="1" {{(old('testedPositiveUsingRTPCRBefore', $records->testedPositiveUsingRTPCRBefore) == 1) ? 'selected' : ''}}>Yes</option>
                                                  <option value="0" {{(is_null(old('testedPositiveUsingRTPCRBefore', $records->testedPositiveUsingRTPCRBefore)) || old('testedPositiveUsingRTPCRBefore', $records->testedPositiveUsingRTPCRBefore) == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="testedPositiveNumOfSwab"><span class="text-danger font-weight-bold">*</span>Number of previous RT-PCR swabs done</label>
                                                <input type="number" class="form-control" name="testedPositiveNumOfSwab" id="testedPositiveNumOfSwab" min="0" value="{{old('testedPositiveNumOfSwab', $records->testedPositiveNumOfSwab)}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divIfTestedPositiveUsingRTPCR">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="testedPositiveSpecCollectedDate"><span class="text-danger font-weight-bold">*</span>Date of Specimen Collection</label>
                                                    <input type="date" class="form-control" name="testedPositiveSpecCollectedDate" id="testedPositiveSpecCollectedDate" max="{{date('Y-m-d')}}" value="{{old('testedPositiveSpecCollectedDate', $records->testedPositiveSpecCollectedDate)}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="testedPositiveLab"><span class="text-danger font-weight-bold">*</span>Laboratory</label>
                                                  <input type="text" class="form-control" name="testedPositiveLab" id="testedPositiveLab" value="{{old('testedPositiveLab', $records->testedPositiveLab)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @if(!($records->ifOldCif()) && $records->getOldCif()->count() > 0)
                                    <div id="accordianId" role="tablist" aria-multiselectable="true">
                                        <div class="card mb-3">
                                            <div class="card-header" role="tab" id="section1HeaderId">
                                                <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                                    Date and Result of Swab Collection from Past CIF (Sorted by Old <i class="fa fa-arrow-up" aria-hidden="true"></i> to Newest <i class="fa fa-arrow-down" aria-hidden="true"></i>)
                                                </a>
                                            </div>
                                            <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                                <div class="card-body">
                                                    @foreach($records->getOldCif()->sortBy('created_at') as $item)
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Date Collected</label>
                                                                <input type="text" class="form-control" value="{{date('m/d/Y', strtotime($item->testDateCollected1))}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Time Collected</label>
                                                                <input type="text" class="form-control" value="{{($item->oniTimeCollected1) ? date('h:i A', strtotime($item->oniTimeCollected1)) : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Date Released</label>
                                                                <input type="text" class="form-control" value="{{($item->testDateReleased1) ? date('m/d/Y', strtotime($item->testDateReleased1)) : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Laboratory</label>
                                                                <input type="text" class="form-control" value="{{($item->testLaboratory1) ? $item->testLaboratory1 : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Type of Test</label>
                                                                <input type="text" class="form-control" value="{{$item->testType1}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Result</label>
                                                                <input type="text" class="form-control" value="{{$item->testResult1}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(!is_null($item->testDateCollected2))
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Date Collected</label>
                                                                <input type="text" class="form-control" value="{{date('m/d/Y', strtotime($item->testDateCollected2))}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Time Collected</label>
                                                                <input type="text" class="form-control" value="{{($item->oniTimeCollected2) ? date('h:i A', strtotime($item->oniTimeCollected2)) : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Date Released</label>
                                                                <input type="text" class="form-control" value="{{($item->testDateReleased2) ? date('m/d/Y', strtotime($item->testDateReleased2)) : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Laboratory</label>
                                                                <input type="text" class="form-control" value="{{($item->testLaboratory2) ? $item->testLaboratory2 : 'N/A'}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Type of Test</label>
                                                                <input type="text" class="form-control" value="{{$item->testType2}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Result</label>
                                                                <input type="text" class="form-control" value="{{$item->testResult2}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if(!($loop->last))
                                                    <hr>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testType1"><span class="text-danger font-weight-bold">*</span>#1 - Type of test</label>
                                                <select class="form-control" name="testType1" id="testType1">
                                                    @if(auth()->user()->isCesuAccount())
                                                    <option value="" {{(is_null(old('testType1', $records->testType1))) ? 'selected' : ''}}>N/A</option>
                                                    @endif
                                                    <option value="OPS" {{(old('testType1', $records->testType1) == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                                    <option value="NPS" {{(old('testType1', $records->testType1) == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                                    <option value="OPS AND NPS" {{(old('testType1', $records->testType1) == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                                    <option value="ANTIGEN" {{(old('testType1', $records->testType1) == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                                    <option value="ANTIBODY" {{(old('testType1', $records->testType1) == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                                    <option value="OTHERS" {{(old('testType1', $records->testType1) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                </select>
                                              </div>
                                              <div id="divTypeOthers1" class="d-none">
                                                  <div class="form-group">
                                                    <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                                    <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1', ($records->testType1 == "ANTIGEN") ? $records->testTypeAntigenRemarks1 : $records->testResultOtherRemarks1)}}" style="text-transform: uppercase;">
                                                  </div>
                                              </div>
                                              <div id="ifAntigen1" class="d-none">
                                                <div class="form-group">
                                                    <label for="antigen_id1">Antigen Kit</label>
                                                    <select class="form-control" name="antigen_id1" id="antigen_id1">
                                                        <option value="" disabled {{(is_null(old('antigen_id1', $records->antigen_id1))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach($antigen_list as $ai)
                                                        <option value="{{$ai->id}}" {{(old('antigen_id1', $records->antigen_id1) == $ai->id) ? 'selected' : ''}}>{{$ai->antigenKitShortName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="antigenLotNo1">Antigen Lot No <small>(Leave Blank to use Default)</small></label>
                                                    <input type="text" class="form-control" name="antigenLotNo1" id="antigenLotNo1" value="{{old('antigenLotNo1', $records->antigenLotNo1)}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date Collected</label>
                                              <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{$mindate}}" max="{{$enddate}}" value="{{old('testDateCollected1', $records->testDateCollected1)}}">
                                              <small class="text-muted">Note: This also considered the first day of Quarantine Period.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="oniTimeCollected1">Time Collected</label>
                                                <input type="time" name="oniTimeCollected1" id="oniTimeCollected1" class="form-control" value="{{old('oniTimeCollected1', $records->oniTimeCollected1)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testLaboratory1">Laboratory <small><i>(Leave Blank if N/A)</i></small></label>
                                                <input type="text" class="form-control" name="testLaboratory1" id="testLaboratory1" value="{{old('testLaboratory1', $records->testLaboratory1)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testResult1"><span class="text-danger font-weight-bold">*</span>Results</label>
                                                <select class="form-control" name="testResult1" id="testResult1" required>
                                                    <option value="PENDING" id="tro1_pending" {{(old('testResult1', $records->testResult1) == 'PENDING') ? 'selected' : ''}}>Pending</option>
                                                    <option value="POSITIVE" id="tro1_positive" {{(old('testResult1', $records->testResult1) == 'POSITIVE') ? 'selected' : ''}}>Positive (will change the Case Classification to 'Confirmed')</option>
                                                    <option value="NEGATIVE" id="tro1_negative" {{(old('testResult1', $records->testResult1) == 'NEGATIVE') ? 'selected' : ''}}>Negative (will change the Case Classification to 'Non-COVID Case')</option>
                                                    <option value="EQUIVOCAL" id="tro1_equivocal" {{(old('testResult1', $records->testResult1) == 'EQUIVOCAL') ? 'selected' : ''}}>Equivocal</option>
                                                    <option value="OTHERS" id="tro1_others" {{(old('testResult1', $records->testResult1) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                </select>
                                              </div>
                                              <div id="divResultOthers1" class="d-none">
                                                  <div class="form-group">
                                                      <label for="testResultOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                                      <input type="text" class="form-control" name="testResultOtherRemarks1" id="testResultOtherRemarks1" value="{{old('testResultOtherRemarks1', ($records->testType1 == "ANTIGEN") ? $records->testTypeAntigenRemarks1 : $records->testResultOtherRemarks1)}}" style="text-transform: uppercase;">
                                                  </div>
                                              </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="ifDateReleased1" class="d-none">
                                                <div class="form-group">
                                                    <label for="testDateReleased1"><span class="text-danger font-weight-bold">*</span>Date Released</label>
                                                    <input type="date" class="form-control" name="testDateReleased1" id="testDateReleased1" value="{{old('testDateReleased1', $records->testDateReleased1)}}" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            @if($records->testType1 == "ANTIGEN")
                                            <div id="antigenExport1" class="d-none">
                                                <a class="btn btn-primary btn-block" href="/forms/printAntigen/{{$records->id}}/1"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print Antigen Result</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testType2"><span class="text-danger font-weight-bold">*</span>#2 - Type of test</label>
                                              <select class="form-control" name="testType2" id="testType2">
                                                    <option value="" {{(is_null(old('testType2', $records->testType2)) == 'OPS') ? 'selected' : ''}}>N/A</option>
                                                    <option value="OPS" {{(old('testType2', $records->testType2) == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                                    <option value="NPS" {{(old('testType2', $records->testType2) == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                                    <option value="OPS AND NPS" {{(old('testType2', $records->testType2) == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                                    <option value="ANTIGEN" {{(old('testType2', $records->testType2) == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                                    <option value="ANTIBODY" {{(old('testType2', $records->testType2) == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                                    <option value="OTHERS" {{(old('testType2', $records->testType2) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                              </select>
                                            </div>
                                            <div id="divTypeOthers2" class="d-none">
                                                <div class="form-group">
                                                  <label for="testTypeOtherRemarks2"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                                  <input type="text" class="form-control" name="testTypeOtherRemarks2" id="testTypeOtherRemarks2" value="{{old('testTypeOtherRemarks2', ($records->testType2 == "ANTIGEN") ? $records->testTypeAntigenRemarks2 : $records->testResultOtherRemarks2)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="ifAntigen2" class="d-none">
                                                <div class="form-group">
                                                    <label for="antigen_id2">Antigen Kit</label>
                                                    <select class="form-control" name="antigen_id2" id="antigen_id2">
                                                        <option value="" disabled {{(is_null(old('antigen_id2', $records->antigen_id2))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach($antigen_list as $ai)
                                                        <option value="{{$ai->id}}" {{(old('antigen_id2', $records->antigen_id2) == $ai->id) ? 'selected' : ''}}>{{$ai->antigenKitShortName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="antigenLotNo2">Antigen Lot No <small>(Leave Blank to use Default)</small></label>
                                                    <input type="text" class="form-control" name="antigenLotNo2" id="antigenLotNo2" value="{{old('antigenLotNo2', $records->antigenLotNo2)}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testDateCollected2"><span class="text-danger font-weight-bold">*</span>Date Collected</label>
                                              <input type="date" class="form-control" name="testDateCollected2" id="testDateCollected2" min="{{$mindate}}" max="{{$enddate}}" value="{{old('testDateCollected2', $records->testDateCollected2)}}">
                                              <small class="text-muted">Note: This also considered the first day of Quarantine Period.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="oniTimeCollected2">Time Collected</label>
                                                <input type="time" name="oniTimeCollected2" id="oniTimeCollected2" class="form-control" value="{{old('oniTimeCollected2', $records->oniTimeCollected2)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="testLaboratory2">Laboratory <small><i>(Leave blank if N/A)</i></small></label>
                                                <input type="text" class="form-control" name="testLaboratory2" id="testLaboratory2" value="{{old('testLaboratory2', $records->testLaboratory2)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="testResult2"><span class="text-danger font-weight-bold">*</span>Results</label>
                                              <select class="form-control" name="testResult2" id="testResult2">
                                                <option value="PENDING" id="tro2_pending" {{(old('testResult2', $records->testResult2) == 'PENDING') ? 'selected' : ''}}>Pending</option>
                                                <option value="POSITIVE" id="tro2_positive" {{(old('testResult2', $records->testResult2) == 'POSITIVE') ? 'selected' : ''}}>Positive (will change the Case Classification to 'Confirmed')</option>
                                                <option value="NEGATIVE" id="tro2_negative" {{(old('testResult2', $records->testResult2) == 'NEGATIVE') ? 'selected' : ''}}>Negative (will change the Case Classification to 'Non-COVID Case')</option>
                                                <option value="EQUIVOCAL" id="tro2_equivocal"{{(old('testResult2', $records->testResult2) == 'EQUIVOCAL') ? 'selected' : ''}}>Equivocal</option>
                                                <option value="OTHERS" id="tro2_others" {{(old('testResult2', $records->testResult2) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                              </select>
                                            </div>
                                            <div id="divResultOthers2" class="d-none">
                                                <div class="form-group">
                                                    <label for="testResultOtherRemarks2"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                                    <input type="text" class="form-control" name="testResultOtherRemarks2" id="testResultOtherRemarks2" value="{{old('testResultOtherRemarks2', $records->testResultOtherRemarks2)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="ifDateReleased2" class="d-none">
                                                <div class="form-group">
                                                    <label for="testDateReleased2">Date Released</label>
                                                    <input type="date" class="form-control" name="testDateReleased2" id="testDateReleased2" value="{{old('testDateReleased2', $records->testDateReleased2)}}" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            @if($records->testType2 == "ANTIGEN")
                                            <div id="antigenExport2" class="d-none">
                                                <a class="btn btn-primary btn-block" href="/forms/printAntigen/{{$records->id}}/2"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print Antigen Result</a>
                                            </div>
                                            @endif
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
                                        <option value="Active" {{(old('outcomeCondition', $records->outcomeCondition) == 'Active') ? 'selected' : ''}}>Active (Currently admitted or in isolation/quarantine)</option>
                                        <option value="Recovered" {{(old('outcomeCondition', $records->outcomeCondition) == 'Recovered') ? 'selected' : ''}}>Recovered</option>
                                        <option value="Died" {{(old('outcomeCondition', $records->outcomeCondition) == 'Died') ? 'selected' : ''}}>Died</option>
                                      </select>
                                      <small class="text-danger d-none" id="outcomeWarningText">Note: When Changing the Outcome to Recovered or Died, the [2.4 Case Classification] of the patient will be automatically set to "Confirmed Case" and this CIF will be locked for editing.</small>
                                    </div>
                                    <div id="ifOutcomeRecovered">
                                        <div class="form-group">
                                          <label for="outcomeRecovDate"><span class="text-danger font-weight-bold">*</span>Date of Recovery</label>
                                          <input type="date" class="form-control" name="outcomeRecovDate" id="outcomeRecovDate" max="{{date('Y-m-d')}}" value="{{old('outcomeRecovDate', $records->outcomeRecovDate)}}">
                                        </div>
                                    </div>
                                    <div id="ifOutcomeDied">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="outcomeDeathDate"><span class="text-danger font-weight-bold">*</span>Date of Death</label>
                                                    <input type="date" class="form-control" name="outcomeDeathDate" id="outcomeDeathDate" max="{{date('Y-m-d')}}" value="{{old('outcomeDeathDate', $records->outcomeDeathDate)}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="deathImmeCause"><span class="text-danger font-weight-bold">*</span>Immediate Cause</label>
                                                    <input type="text" class="form-control" name="deathImmeCause" id="deathImmeCause" value="{{old('deathImmeCause', $records->deathImmeCause)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathAnteCause">Antecedent Cause</label>
                                                    <input type="text" class="form-control" name="deathAnteCause" id="deathAnteCause" value="{{old('deathAnteCause', $records->deathAnteCause)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Underlying Cause</label>
                                                    <input type="text" class="form-control" name="deathUndeCause" id="deathUndeCause" value="{{old('deathUndeCause', $records->deathUndeCause)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Contributory Conditions</label>
                                                    <input type="text" class="form-control" name="contriCondi" id="contriCondi" value="{{old('contriCondi', $records->contriCondi)}}" style="text-transform: uppercase;">
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
                                            <option id="sexpoitem1_no" value="2" {{(old('expoitem1', $records->expoitem1) == 2) ? 'selected' : ''}}>No</option>
                                            <option value="1" {{(old('expoitem1', $records->expoitem1) == 1) ? 'selected' : ''}}>Yes</option>
                                            <option id="sexpoitem1_unknown" value="3" {{(old('expoitem1', $records->expoitem1) == 3) ? 'selected' : ''}}>Unknown</option>
                                      </select>
                                    </div>
                                    <div id="divExpoitem1">
                                        <div class="form-group">
                                          <label for=""><span class="text-danger font-weight-bold">*</span>Date of Last Contact/Exposure to COVID-19 Positive Area or Patient</label>
                                          <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont', $records->expoDateLastCont)}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="expoitem2"><span class="text-danger font-weight-bold">*</span>Has the patient been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                        <select class="form-control" name="expoitem2" id="expoitem2" required>
                                          <option value="0" id="expoitem2_sno" {{(old('expoitem2', $records->expoitem2) == 0) ? 'selected' : ''}}>No</option>
                                          <option value="1" {{(old('expoitem2', $records->expoitem2) == 1) ? 'selected' : ''}}>Yes, Local</option>
                                          <option value="2" {{(old('expoitem2', $records->expoitem2) == 2) ? 'selected' : ''}}>Yes, International</option>
                                          <option value="3" {{(old('expoitem2', $records->expoitem2) == 3) ? 'selected' : ''}}>Unknown exposure</option>
                                        </select>
                                    </div>
                                    <div id="divTravelInt">
                                        <div class="form-group">
                                            <label for="intCountry"><span class="text-danger font-weight-bold">*</span>If International Travel, country of origin</label>
                                            <select class="form-control" name="intCountry" id="intCountry">
                                                <option value="" {{(is_null(old('intCountry', $records->intCountry))) ? 'selected disabled' : ''}}>Choose...</option>
                                                  @foreach ($countries as $country)
                                                      @if($country != 'Philippines')
                                                          <option value="{{$country}}" {{(old('intCountry', $records->intCountry) == $country) ? 'selected' : ''}}>{{$country}}</option>
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
                                                                  <input type="date" class="form-control" name="intDateFrom" id="intDateFrom" value="{{old('intDateFrom', $records->intDateFrom)}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="intDateTo">From</label>
                                                                    <input type="date" class="form-control" name="intDateTo" id="intDateTo" value="{{old('intDateTo', $records->intDateTo)}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="intWithOngoingCovid">With ongoing COVID-19 community transmission?</label>
                                                    <select class="form-control" name="intWithOngoingCovid" id="intWithOngoingCovid">
                                                      <option value="NO" {{(old('intWithOngoingCovid', $records->intWithOngoingCovid) == "NO") ? 'selected' : ''}}>No</option>
                                                      <option value="YES" {{(old('intWithOngoingCovid', $records->intWithOngoingCovid) == "YES") ? 'selected' : ''}}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="intVessel">Airline/Sea vessel</label>
                                                          <input type="text" class="form-control" name="intVessel" id="intVessel" value="{{old('intVessel', $records->intVessel)}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intVesselNo">Flight/Vessel Number</label>
                                                            <input type="text" class="form-control" name="intVesselNo" id="intVesselNo" value="{{old('intVesselNo', $records->intVesselNo)}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateDepart">Date of departure</label>
                                                            <input type="date" class="form-control" name="intDateDepart" id="intDateDepart" value="{{old('intDateDepart', $records->intDateDepart)}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateArrive">Date of arrival in PH</label>
                                                            <input type="date" class="form-control" name="intDateArrive" id="intDateArrive" value="{{old('intDateArrive', $records->intDateArrive)}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divTravelLoc" class="localTravelOptions">
                                        <div class="card">
                                            <div class="card-header">
                                                If Local Travel, specify travel places (<i>Check all that apply, provide name of facility, address, and inclusive travel dates</i>)
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited1" value="Health Facility" {{(in_array("Health Facility", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                        Health Facility
                                                      </label>
                                                </div>
                                                <div id="divLocal1" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName1">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName1" id="locName1" value="{{old('locName1', $records->locName1)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress1">Location</label>
                                                                <input class="form-control" type="text" name="locAddress1" id="locAddress1" value="{{old('locAddress1', $records->locAddress1)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom1">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom1" id="locDateFrom1" value="{{old('locDateFrom1', $records->locDateFrom1)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo1">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo1" id="locDateTo1" value="{{old('locDateTo1', $records->locDateTo1)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid1">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid1" id="locWithOngoingCovid1">
                                                                <option value="NO" {{(old('locWithOngoingCovid1', $records->locWithOngoingCovid1) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid1', $records->locWithOngoingCovid1) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited2" value="Closed Settings" {{(in_array("Closed Settings", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Closed Settings
                                                    </label>
                                                </div>
                                                <div id="divLocal2" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName2">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName2" id="locName2" value="{{old('locName2', $records->locName2)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress2">Location</label>
                                                                <input class="form-control" type="text" name="locAddress2" id="locAddress2" value="{{old('locAddress2', $records->locAddress2)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom2">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom2" id="locDateFrom2" value="{{old('locDateFrom2', $records->locDateFrom2)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo2">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo2" id="locDateTo2" value="{{old('locDateTo2', $records->locDateTo2)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid2">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid2" id="locWithOngoingCovid2">
                                                                <option value="NO" {{(old('locWithOngoingCovid2', $records->locWithOngoingCovid2) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid2', $records->locWithOngoingCovid2) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited3" value="School" {{(in_array("School", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      School
                                                    </label>
                                                </div>
                                                <div id="divLocal3" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName3">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName3" id="locName3" value="{{old('locName3', $records->locName3)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress3">Location</label>
                                                                <input class="form-control" type="text" name="locAddress3" id="locAddress3" value="{{old('locAddress3', $records->locAddress3)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom3">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom3" id="locDateFrom3" value="{{old('locDateFrom3', $records->locDateFrom3)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo3">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo3" id="locDateTo3" value="{{old('locDateTo3', $records->locDateTo3)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid3">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid3" id="locWithOngoingCovid3">
                                                                <option value="NO" {{(old('locWithOngoingCovid3', $records->locWithOngoingCovid3) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid3', $records->locWithOngoingCovid3) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited4" value="Workplace" {{(in_array("Workplace", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Workplace
                                                    </label>
                                                </div>
                                                <div id="divLocal4" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName4">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName4" id="locName4" value="{{old('locName4', $records->locName4)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress4">Location</label>
                                                                <input class="form-control" type="text" name="locAddress4" id="locAddress4" value="{{old('locAddress4', $records->locAddress4)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom4">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom4" id="locDateFrom4" value="{{old('locDateFrom4', $records->locDateFrom4)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo4">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo4" id="locDateTo4" value="{{old('locDateTo4', $records->locDateTo4)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid4">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid4" id="locWithOngoingCovid4">
                                                                <option value="NO" {{(old('locWithOngoingCovid4', $records->locWithOngoingCovid4) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid4', $records->locWithOngoingCovid4) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited5" value="Market" {{(in_array("Market", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Market
                                                    </label>
                                                </div>
                                                <div id="divLocal5" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName5">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName5" id="locName5" value="{{old('locName5', $records->locName5)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress5">Location</label>
                                                                <input class="form-control" type="text" name="locAddress5" id="locAddress5" value="{{old('locAddress5', $records->locAddress5)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom5">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom5" id="locDateFrom5" value="{{old('locDateFrom5', $records->locDateFrom5)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo5">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo5" id="locDateTo5" value="{{old('locDateTo5', $records->locDateTo5)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid5">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid5" id="locWithOngoingCovid5">
                                                                <option value="NO" {{(old('locWithOngoingCovid5', $records->locWithOngoingCovid5) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid5', $records->locWithOngoingCovid5) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited6" value="Social Gathering" {{(in_array("Social Gathering", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Social Gathering
                                                    </label>
                                                </div>
                                                <div id="divLocal6" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName6">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName6" id="locName6" value="{{old('locName6', $records->locName6)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress6">Location</label>
                                                                <input class="form-control" type="text" name="locAddress6" id="locAddress6" value="{{old('locAddress6', $records->locAddress6)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom6">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom6" id="locDateFrom6" value="{{old('locDateFrom6', $records->locDateFrom6)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo6">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo6" id="locDateTo6" value="{{old('locDateTo6', $records->locDateTo6)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid6">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid6" id="locWithOngoingCovid6">
                                                                <option value="NO" {{(old('locWithOngoingCovid6', $records->locWithOngoingCovid6) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid6', $records->locWithOngoingCovid6) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited7" value="Others" {{(in_array("Others", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Others
                                                    </label>
                                                </div>
                                                <div id="divLocal7" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName7">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName7" id="locName7" value="{{old('locName7', $records->locName7)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress7">Location</label>
                                                                <input class="form-control" type="text" name="locAddress7" id="locAddress7" value="{{old('locAddress7', $records->locAddress7)}}" style="text-transform: uppercase;">
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
                                                                                <label for="locDateFrom7">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom7" id="locDateFrom7" value="{{old('locDateFrom7', $records->locDateFrom7)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo7">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo7" id="locDateTo7" value="{{old('locDateTo7', $records->locDateTo7)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid7">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid7" id="locWithOngoingCovid7">
                                                                <option value="NO" {{(old('locWithOngoingCovid7', $records->locWithOngoingCovid7) == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid7', $records->locWithOngoingCovid7) == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited8" value="Transport Service" {{(in_array("Transport Service", old('placevisited', explode(",", $records->placevisited)))) ? 'checked' : ''}}>
                                                      Transport Service
                                                    </label>
                                                </div>
                                                <div id="divLocal8" class="my-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel1">1. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel1" id="localVessel1" value="{{old('localVessel1', $records->localVessel1)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo1">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo1" id="localVesselNo1" value="{{old('localVesselNo1', $records->localVesselNo1)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin1">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin1" id="localOrigin1" value="{{old('localOrigin1', $records->localOrigin1)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart1">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart1" id="localDateDepart1" value="{{old('localDateDepart1', $records->localDateDepart1)}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest1">Destination</label>
                                                                <input type="text" class="form-control" name="localDest1" id="localDest1" value="{{old('localDest1', $records->localDest1)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive1">Date of Arrival</label>
                                                                <input type="text" class="form-control" name="localDateArrive1" id="localDateArrive1" value="{{old('localDateArrive1', $records->localDateArrive1)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel2">2. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel2" id="localVessel2" value="{{old('localVessel2', $records->localVessel2)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo2">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo2" id="localVesselNo2" value="{{old('localVesselNo2', $records->localVesselNo2)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin2">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin2" id="localOrigin2" value="{{old('localOrigin2', $records->localOrigin2)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart2">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart2" id="localDateDepart2" value="{{old('localDateDepart2', $records->localDateDepart2)}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest2">Destination</label>
                                                                <input type="text" class="form-control" name="localDest2" id="localDest2" value="{{old('localDest2', $records->localDest2)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive2">Date of Arrival</label>
                                                                <input type="date" class="form-control" name="localDateArrive2" id="localDateArrive2" value="{{old('localDateArrive2', $records->localDateArrive2)}}" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">List the names of persons who were with you two days prior to onset of illness until this date and their contact numbers.</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="alert alert-info" role="alert">
                                                        <p>- If symptomatic, provide names and contact numbers of persons who were with the patient two days prior to onset of illness until this date.</p>
                                                        <p>- If asymptomatic, provide names and contact numbers of persons who were with the patient on the day specimen was submitted for testing until this date.</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="card">
                                                        <div class="card-header">Name</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                              <input type="text" class="form-control" name="contact1Name" id="contact1Name" value="{{old('contact1Name', $records->contact1Name)}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2Name" id="contact2Name" value="{{old('contact2Name', $records->contact2Name)}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3Name" id="contact3Name" value="{{old('contact3Name', $records->contact3Name)}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4Name" id="contact4Name" value="{{old('contact4Name', $records->contact4Name)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-header">Contact Number</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact1No" id="contact1No" value="{{old('contact1No', $records->contact1No)}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2No" id="contact2No" value="{{old('contact2No', $records->contact2No)}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3No" id="contact3No" value="{{old('contact3No', $records->contact3No)}}" pattern="[0-9]{11}" placeholder="09*********">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4No" id="contact4No" value="{{old('contact4No', $records->contact4No)}}" pattern="[0-9]{11}" placeholder="09*********">
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
                                                <div>Link Primary CC of {{$records->records->getName()}} (To be filled by Contact Tracers ONLY)</div>
                                                <div><a class="btn btn-outline-success" href="{{route('ct_exposure_create', ['form_id' => $records->id])}}" role="button"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add Primary CC</a></div>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($get_ctdata->count() != 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Date Encoded</th>
                                                            <th>Primary CC Name / CIF ID</th>
                                                            <th>Exposure Date</th>
                                                            <th>Encoded By</th>
                                                            @if(auth()->user()->ifTopAdmin() || auth()->user()->id == $ctitem->user_id)
                                                            <th></th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($get_ctdata as $ctitem)
                                                        <tr>
                                                            <td><a href="{{route('ct_exposure_edit', ['form_id' => $records->id, 'ct_id' => $ctitem->id])}}">{{date('m/d/Y h:i A', strtotime($ctitem->created_at))}}</a></td>
                                                            <td><a href="{{route('forms.edit', ['form' => $ctitem->cif_linkid])}}">{{$ctitem->getCifLinkRecords()->records->getName()}} <small>(#{{$ctitem->cif_linkid}})</small></a></td>
                                                            <td>{{date('m/d/Y', strtotime($ctitem->exposure_date))}}</td>
                                                            <td>{{$ctitem->user->name}}</td>
                                                            @if(auth()->user()->ifTopAdmin() || auth()->user()->id == $ctitem->user_id)
                                                            <td>
                                                                <form action="{{route('ct_exposure_delete', ['ct_id' => $ctitem->id])}}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('The selected Primary CC of this Patient will be deleted. Click OK to Proceed.')"><i class="fa fa-trash"></i></button>
                                                                </form>
                                                            </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @else
                                            <p>There are no Exposure History recorded in this CIF of the Patient.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    @if($records->ifOldCIf())
                        @if(auth()->user()->ifTopAdmin())
                        <button type="submit" class="btn btn-primary" id="formsubmit" onclick="return confirm('Warning: You are updating an OLD CIF of Patient. Please check the details before proceeding. After checking, Click OK to proceed.')"><i class="fas fa-edit mr-2"></i>Update (CTRL + S)</button>
                        @else
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="OLD CIF of Patient can only be updated by an admin.">
                            <button class="btn btn-primary" style="pointer-events: none;" type="button" disabled><i class="fas fa-edit mr-2"></i>Update (CTRL + S)</button>
                        </span>
                        @endif
                    @else
                        @if($records->ifEligibleToUpdate())
                        <button type="submit" class="btn btn-primary" id="formsubmit"><i class="fas fa-edit mr-2"></i>Update (CTRL + S)</button>
                        @else
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Cannot Update this CIF of Patient. This CIF was already finished.">
                            <button class="btn btn-primary" style="pointer-events: none;" type="button" disabled><i class="fas fa-edit mr-2"></i>Update (CTRL + S)</button>
                        </span>
                        @endif
                    @endif
                </div>
            </div>
        </form>
        <hr>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        Documents
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadDoc"><i class="fa fa-upload mr-2" aria-hidden="true"></i>Upload</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($docs->count())
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Date Uploaded</th>
                                <th>By</th>
                                <th>Remarks</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docs as $doc)
                            <tr>
                                <td scope="row">{{$loop->iteration}}</td>
                                <td>{{$doc->file_type}}</td>
                                <td>{{date('m/d/Y h:i A', strtotime($doc->created_at))}}</td>
                                <td>{{$doc->user->name}}</td>
                                <td>{{$doc->remarks}}</td>
                                <td><a href="/forms/download/{{$doc->id}}" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">There are no documents uploaded for this record yet.</p>
                @endif
            </div>
        </div>

        <form action="{{route('msheet.create', ['forms_id' => $records->id])}}" method="POST" id="msheetform">
            @csrf
        </form>

        <form action="/forms/{{$records->id}}/edit" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal fade" id="uploadDoc" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Document</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                              <label for="file_type"><span class="text-danger font-weight-bold">*</span>Document Type</label>
                              <select class="form-control" name="file_type" id="file_type" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="RESULT">Result</option>
                                    <option value="REQUIREMENTS">Requirements</option>
                              </select>
                            </div>
                            <div class="form-group">
                                <label for="filepath"><span class="text-danger font-weight-bold">*</span>Select File</label>
                                <input type="file" class="form-control-file" name="filepath" id="filepath" required>
                            </div>
                            <div class="form-group">
                              <label for="remarks">Remarks</label>
                              <input type="text" class="form-control" name="remarks" id="remarks">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

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
                confirm('You are encoding {{$records->records->getName()}} as a POSITIVE (+) Case. Please double check carefully and Click OK to Confirm.');
            }
            else if($('#caseClassification').val() == 'Non-COVID-19 Case') {
                confirm('You are encoding {{$records->records->getName()}} as a NEGATIVE (-) Case. Please double check carefully and  Click OK to Confirm.');
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

            var getCurrentClassification = '{{$records->caseClassification}}';

            //For Reinfection
            $('#caseClassification').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 'Confirmed') {
                    $('#cutoffwarning').removeClass('d-none');
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

                    //Change MM if Positive
                    if(getCurrentClassification != 'Confirmed' && $('#caseClassification').val() == 'Confirmed') {
                        $('#morbidityMonth').prop('min', "{{date('Y-m-d')}}");
                    }
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

                    if(getCurrentClassification != 'Non-COVID-19 Case' && $('#caseClassification').val() == 'Non-COVID-19 Case') {
                        $('#morbidityMonth').prop('min', "{{date('Y-m-d')}}");
                    }
                }
                else {
                    $('#cutoffwarning').addClass('d-none');
                    $('#askIfReinfected').hide();
                    $('#confirmedVariant').hide();

                    $('#testType1').prop('required', false);
                    $('#testResult1').val("PENDING");
                    $('#testResult1').trigger('change');
                    $('#tro1_pending').removeClass('d-none');
                    $('#tro1_positive').removeClass('d-none');
                    $('#tro1_negative').removeClass('d-none');
                    $('#tro1_equivocal').removeClass('d-none');
                    $('#tro1_others').removeClass('d-none');
                    
                    $('#testResult2').val("PENDING");
                    $('#tro2_pending').removeClass('d-none');
                    $('#tro2_positive').removeClass('d-none');
                    $('#tro2_negative').removeClass('d-none');
                    $('#tro2_equivocal').removeClass('d-none');
                    $('#tro2_others').removeClass('d-none');

                    $('#morbidityMonth').prop('min', "2020-01-01");
                }
            }).trigger('change');
            
            @if(!is_null($records->informantRelationship))
                $('#informantRelationship').val("{{$records->informantRelationship}}");
            @endif

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

            var getCurrentPtype = $('#pType').val();
            var getCurrentExpo1 = $('#expoitem1').val();
            var getCurrentExpo2 = $('#expoitem2').val();
            
            $(function(){
                var requiredCheckboxes = $(".symptomsList :checkbox");
                requiredCheckboxes.change(function() {
                    if(requiredCheckboxes.is(':checked')) {
                        $('#dateOnsetOfIllness').prop('required', true);
                        $('#pType').val('PROBABLE');
                        $('#expoitem1').val('1').change();
                        $('#sexpoitem1_no').addClass('d-none');
                        $('#sexpoitem1_unknown').addClass('d-none');

                        $('#expoitem2').val('1').change();
                        $('#expoitem2_sno').addClass('d-none');
                    } else {
                        $('#dateOnsetOfIllness').prop('required', false);
                        $('#pType').val(getCurrentPtype);
                        $('#expoitem1').val(getCurrentExpo1).change();
                        $('#sexpoitem1_no').removeClass('d-none');
                        $('#sexpoitem1_unknown').removeClass('d-none');

                        $('#expoitem2').val(getCurrentExpo2).change();
                        $('#expoitem2_sno').removeClass('d-none');
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

            $('#dispositionType').change(function (e) {
                e.preventDefault();
                $('#dispositionDate').prop("type", "datetime-local");
                
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
                    $('#comCheck2').prop({'disabled': false});
                    $('#comCheck3').prop({'disabled': false});
                    $('#comCheck4').prop({'disabled': false});
                    $('#comCheck5').prop({'disabled': false});
                    $('#comCheck6').prop({'disabled': false});
                    $('#comCheck7').prop({'disabled': false});
                    $('#comCheck8').prop({'disabled': false});
                    $('#comCheck9').prop({'disabled': false});
                    $('#comCheck10').prop({'disabled': false});
                    $('#comCheck11').prop({'disabled': false});
                    $('#comCheck12').prop({'disabled': false});
                    $('#comCheck13').prop({'disabled': false});
                }
            });

            @if(in_array("None", old('comCheck', explode(",", $records->COMO))))
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

            if($('#imagingDone').val() != "N/A") {
                $('#imagingResult option[value="{{$records->imagingResult}}"]').prop('selected', true);
            }
            
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

                    $('#antigenExport1').addClass('d-none');
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
                            $('#antigenExport1').removeClass('d-none');
                            $('#ifAntigen1').removeClass('d-none');
                            $('#antigen_id1').prop('required', true);
                        }
                        else {
                            $('#antigenExport1').addClass('d-none');
                            $('#ifAntigen1').addClass('d-none');
                            $('#antigen_id1').prop('required', false);
                        }
                    }
                    else {
                        $('#divTypeOthers1').addClass('d-none');
                        $('#testTypeOtherRemarks1').empty();
                        $('#testTypeOtherRemarks1').prop('required', false);

                        $('#antigenExport1').addClass('d-none');
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
                    $('#divResultOthers1').removeClass('d-none');
                    $('#testResultOtherRemarks1').prop('required', true);
                }
                else {
                    $('#divResultOthers1').addClass('d-none');
                    $('#testResultOtherRemarks1').empty();
                    $('#testResultOtherRemarks1').prop('required', false);

                    if($(this).val() == "POSITIVE" || $(this).val() == "NEGATIVE" || $(this).val() == "EQUIVOCAL") {
                        $('#testDateReleased1').prop('required', true);
                        $('#asterisk_date_released1').removeClass('d-none');
                        $('#ifDateReleased1').removeClass('d-none');

                        $('#testLaboratory1').prop('required', true);
                    }
                    else {
                        $('#testDateReleased1').prop('required', false);
                        $('#asterisk_date_released1').addClass('d-none');
                        $('#ifDateReleased1').addClass('d-none');

                        $('#testLaboratory1').prop('required', false);
                    }
                }

                if($(this).val() == 'POSITIVE') {
                    if($('#caseClassification').val() != 'Confirmed') {
                        $('#caseClassification').val('Confirmed');
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
                    
                    $('#antigenExport2').addClass('d-none');
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
                            $('#antigenExport2').removeClass('d-none');
                            $('#ifAntigen2').removeClass('d-none');
                            $('#antigen_id2').prop('required', true);
                        }
                        else {
                            $('#antigenExport2').addClass('d-none');
                            $('#ifAntigen2').addClass('d-none');
                            $('#antigen_id2').prop('required', false);
                        }
                    }
                    else {
                        $('#divTypeOthers2').addClass('d-none');
                        $('#testTypeOtherRemarks2').empty();
                        $('#testTypeOtherRemarks2').prop('required', false);
                        
                        $('#antigenExport2').addClass('d-none');
                        $('#ifAntigen2').addClass('d-none');
                        $('#antigen_id2').prop('required', false);
                    }
                }
            }).trigger('change');

            $('#testResult2').change(function (e) {
                e.preventDefault();
                if($(this).val() == "OTHERS") {
                    $('#divResultOthers2').removeClass('d-none');
                    $('#testResultOtherRemarks2').prop('required', true);
                }
                else {
                    $('#divResultOthers2').addClass('d-none');
                    $('#testResultOtherRemarks2').empty();
                    $('#testResultOtherRemarks2').prop('required', false);

                    if($(this).val() == "POSITIVE" || $(this).val() == "NEGATIVE" || $(this).val() == "EQUIVOCAL") {
                        $('#testDateReleased2').prop('required', true);
                        $('#ifDateReleased2').removeClass('d-none');

                        $('#testLaboratory2').prop('required', true);
                    }
                    else {
                        $('#testDateReleased2').prop('required', false);
                        $('#ifDateReleased2').addClass('d-none');

                        $('#testLaboratory2').prop('required', false);
                    }
                }

                if($(this).val() == 'POSITIVE') {
                    if($('#caseClassification').val() != 'Confirmed') {
                        $('#caseClassification').val('Confirmed');
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
                    url: "{{route('forms.ajaxcclist')}}?self_id={{$records->records->id}}",
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

            $('#is_disobedient').change(function (e) {
                if(this.checked) {
                    $('#disobedient_div').removeClass('d-none');
                    $('#disobedient_remarks').prop('required', true);
                }
                else {
                    $('#disobedient_div').addClass('d-none');
                    $('#disobedient_remarks').prop('required', false);
                    $('#disobedient_remarks').val('');
                }
            }).trigger('change');
        });
    </script>
@endsection