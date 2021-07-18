@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="font-weight-bold">
                Case Investigation Form List
                </div>
                <div>
                    @if(auth()->user()->isCesuAccount())
                    <a href="{{route('forms.antigenlinelist.print')}}" class="btn btn-primary">Print Antigen Linelist</a>
                    @endif
                    @if(auth()->user()->isCesuAccount())
                    <a href="{{route('paswab.view')}}" class="btn btn-primary">View Pa-Swab List <span class="text-warning">({{$paswabctr}})</span></a>
                    @endif
                    @if($records->count() > 0)
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#selectPatient"><i class="fa fa-plus mr-2" aria-hidden="true"></i>New/Search CIF</button>
                    @else
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Create patient record first to process CIF.">
                        <button class="btn btn-success" style="pointer-events: none;" type="button" disabled>New/Search CIF</button>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-{{session('statustype')}}" role="alert">
                    {{session('status')}}
                </div>
            @endif

            <form action="{{route('forms.index')}}" method="GET">
                <div id="accordianId" role="tablist" aria-multiselectable="true">
                    <div class="card mb-3">
                        <div class="card-header" role="tab" id="section1HeaderId">
                            <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                Filter Data
                            </a>
                        </div>
                        <div id="section1ContentId" class="collapse in {{(request()->get('view')) ? 'show' : ''}}" role="tabpanel" aria-labelledby="section1HeaderId">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="view">Filter Type</label>
                                    <select class="form-control" name="view" id="view" required>
                                      <option value="1" {{(request()->get('view') == '1') ? 'selected' : ''}}>Show All Records</option>
                                      <option value="2" {{(request()->get('view') == '2') ? 'selected' : ''}}>Show All Except Records that has less than 5 Days Exposure History from this day</option>
                                      <option value="3" {{(request()->get('view') == '3') ? 'selected' : ''}}>Show All Except Records that has not been exported to Excel yet</option>
                                      </select>
                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label for="sdate">Starting Date</label>
                                            <input type="date" class="form-control" name="sdate" id="sdate" value="{{(request()->get('view')) ? request()->get('sdate') : date('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edate">Ending Date</label>
                                                <input type="date" class="form-control" name="edate" id="edate" value="{{(request()->get('view')) ? request()->get('edate') : date('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-filter mr-2"></i>Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="alert alert-info" role="alert">
                @if(request()->input('view') != null)
                    @if(request()->input('sdate') != request()->input('edate'))
                        <span>Swab List from {{date('m/d/Y', strtotime(request()->input('sdate')))}} to {{date('m/d/Y', strtotime(request()->input('edate')))}}. Total count = <b>{{count($forms)}}</b> (With Philhealth: {{$formsctr->whereNotNull('records.philhealth')->count()}} | Without Philhealth: {{$formsctr->whereNull('records.philhealth')->count()}})</span>
                    @else
                    <span>Swab List for {{date('m/d/Y', strtotime(request()->input('sdate')))}}. Total count = <b>{{count($forms)}}</b> (With Philhealth: {{$formsctr->whereNotNull('records.philhealth')->count()}} | Without Philhealth: {{$formsctr->whereNull('records.philhealth')->count()}})</span>
                    @endif
                @else
                <span>Swab List for Today ({{date('m/d/Y')}}). Total count = <b>{{count($forms)}}</b> (With Philhealth: {{$formsctr->whereNotNull('records.philhealth')->count()}} | Without Philhealth: {{$formsctr->whereNull('records.philhealth')->count()}})</span>
                @endif
                <hr>
                <span>For Hospitalization: {{$formsctr->where('isForHospitalization', 1)->count()}} | Pregnant: {{$formsctr->where('records.isPregnant', 1)->count()}} | Printed: {{$formsctr->where('isExported', 1)->count()}} | Not Printed: {{$formsctr->where('isExported', 0)->count()}}</span>
                <hr>
                <span>
                    <b>OPS</b>: {{$formsctr->where('testType1','OPS')->merge($formsctr->where('testType2', 'OPS'))->count()}} (W/PH: {{$formsctr->where('testType1', 'OPS')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'OPS')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'OPS')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'OPS')->whereNull('records.philhealth'))->count()}}) | 
                    <b>NPS</b>: {{$formsctr->where('testType1','NPS')->merge($formsctr->where('testType2', 'NPS'))->count()}} (W/PH: {{$formsctr->where('testType1', 'NPS')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'NPS')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'NPS')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'NPS')->whereNull('records.philhealth'))->count()}}) | 
                    <b>OPS & NPS</b>: {{$formsctr->where('testType1','OPS AND NPS')->merge($formsctr->where('testType2', 'OPS AND NPS'))->count()}} (W/PH: {{$formsctr->where('testType1', 'OPS AND NPS')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'OPS AND NPS')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'OPS AND NPS')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'OPS AND NPS')->whereNull('records.philhealth'))->count()}}) | 
                    <b>Antigen</b>: {{$formsctr->where('testType1','ANTIGEN')->merge($formsctr->where('testType2', 'ANTIGEN'))->count()}} (W/PH: {{$formsctr->where('testType1', 'ANTIGEN')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'ANTIGEN')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'ANTIGEN')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'ANTIGEN')->whereNull('records.philhealth'))->count()}}) | 
                    <b>Antibody</b>: {{$formsctr->where('testType1','ANTIBODY')->merge($formsctr->where('testType2', 'ANTIBODY'))->count()}} (W/PH: {{$formsctr->where('testType1', 'ANTIBODY')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'ANTIBODY')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'ANTIBODY')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'ANTIBODY')->whereNull('records.philhealth'))->count()}}) | 
                    <b>Others</b>: {{$formsctr->where('testType1','OTHERS')->merge($formsctr->where('testType2', 'OTHERS'))->count()}} (W/PH: {{$formsctr->where('testType1', 'OTHERS')->whereNotNull('records.philhealth')->merge($formsctr->where('testType2', 'OTHERS')->whereNotNull('records.philhealth'))->count()}} - W/O PH: {{$formsctr->where('testType1', 'OTHERS')->whereNull('records.philhealth')->merge($formsctr->where('testType2', 'OTHERS')->whereNull('records.philhealth'))->count()}})
                </span>
            </div>

            <form action="{{route('forms.options')}}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="table_id">
                        <thead>
                            <tr>
                                <th colspan="21" class="text-left">
                                    <button type="button" class="btn btn-primary" id="changeTypeBtn" data-toggle="modal" data-target="#changeTypeModal"><i class="fas fa-vials mr-2"></i>Change Test Type</button>
                                    <button type="button" class="btn btn-primary" id="reschedBtn" data-toggle="modal" data-target="#reschedModal"><i class="fas fa-user-clock mr-2"></i>Re-schedule</button>
                                    <button type="submit" class="btn btn-primary" id="exportBtn" name="submit" value="export"><i class="fas fa-file-download mr-2"></i>Export to Excel</button>
                                </th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th style="vertical-align: middle;"><input type="checkbox" class="checks mx-2" name="" id="select_all"></th>
                                <th style="vertical-align: middle;">Name</th>
                                <th style="vertical-align: middle;">Philhealth</th>
                                <th style="vertical-align: middle;">Mobile</th>
                                <th style="vertical-align: middle;">Birthdate</th>
                                <th style="vertical-align: middle;">Age/Gender</th>
                                <th style="vertical-align: middle;">Street</th>
                                <th style="vertical-align: middle;">Brgy</th>
                                <th style="vertical-align: middle;">City</th>
                                <th style="vertical-align: middle;">Type of Client</th>
                                <th style="vertical-align: middle;">Health Status</th>
                                <th style="vertical-align: middle;">Case Classification</th>
                                <th style="vertical-align: middle;">Hospitalization</th>
                                <th style="vertical-align: middle;">Referral Code</th>
                                <th style="vertical-align: middle;">Date of Collection</th>
                                <th style="vertical-align: middle;">Test Type</th>
                                <th style="vertical-align: middle;">Status</th>
                                <th style="vertical-align: middle;">Encoded By</th>
                                <th style="vertical-align: middle;">Encoded At</th>
                                <th style="vertical-align: middle;">Printed?</th>
                                <th style="vertical-align: middle;">Attended?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($forms as $form)
                                @php
                                if($form->pType == "PROBABLE") {
                                    $pTypeStr = "COVID-19 CASE (".strtoupper($form->caseClassification).")";
                                }
                                else if($form->pType == 'CLOSE CONTACT') {
                                    $pTypeStr = "CLOSE CONTACT";
                                }
                                else {
                                    $pTypeStr = "NOT A CASE OF COVID";
                                }

                                if(is_null($form->expoDateLastCont)) {
                                    $edate = "N/A";
                                } 
                                else {
                                    $edate = date('m/d/Y', strtotime($form->expoDateLastCont));
                                }

                                if($form->isExported == 1) {
                                    $textcolor = 'success';
                                }
                                else {
                                    $textcolor = 'warning';
                                }

                                if(!is_null($form->isPresentOnSwabDay)) {
                                    if($form->isPresentOnSwabDay == 1) {
                                        $attendedText = 'YES';
                                        $textcolor = 'success';
                                    }
                                    else if($form->isPresentOnSwabDay == 0) {
                                        $attendedText = 'NO';
                                        $textcolor = 'danger';
                                    }
                                }
                                else {
                                    $attendedText = '';
                                }
                            @endphp
                            <tr class="bg-{{$textcolor}}">
                                <th class="text-center" style="vertical-align: middle;">
                                    <input type="checkbox" class="checks mx-2" name="listToPrint[]" id="" value="{{$form->id}}">
                                </th>
                                <td style="vertical-align: middle;">
                                    <a href="forms/{{$form->id}}/edit" class="text-dark">{{$form->records->lname}}, {{$form->records->fname}} {{$form->records->mname}}</a> 
                                </td>
                                <td style="vertical-align: middle;" class="text-center">{{(!is_null($form->records->philhealth)) ? $form->records->philhealth : 'N/A'}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->records->mobile}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{date('m/d/Y', strtotime($form->records->bdate))}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->records->getAge()}} / {{$form->records->gender}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->records->address_street}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->records->address_brgy}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->records->address_city}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$pTypeStr}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{strtoupper($form->healthStatus)}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{strtoupper($form->caseClassification)}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{($form->isForHospitalization == 1) ? 'YES' : 'NO'}}</td>
                                <td style="vertical-align: middle;" class="text-center"><small>{{$form->getReferralCode()}}</small></td>
                                <td style="vertical-align: middle;" class="text-center">{{(!is_null($form->testDateCollected2)) ? $form->testDateCollected2 : $form->testDateCollected1}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{(!is_null($form->testDateCollected2)) ? $form->testType2 : $form->testType1}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{(!is_null($form->testDateCollected2)) ? $form->testResult2 : $form->testResult1}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$form->user->name}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{date("m/d/Y h:i A", strtotime($form->created_at))}}</td>
                                
                                <td style="vertical-align: middle;" class="text-center">{{($form->isExported == 1) ? 'YES' : 'NO'}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$attendedText}}</td>
                            </tr>
                            @empty
                            
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="reschedModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Re-scheduling</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="reschedDate">Specify date where CIF will be re-scheduled</label>
                                  <input type="date" class="form-control" name="reschedDate" id="reschedDate">
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="changeToMorning" id="changeToMorning" value="1">
                                    Change Time of Collection to Morning <small>(for ONI, will start at 9:30 AM)</small>
                                  </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit" value="resched">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeTypeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Change Test Type</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="changeType"><span class="text-danger font-weight-bold">*</span>Specify Type of Test where the selected CIF test type will be changed</label>
                                  <select class="form-control" name="changeType" id="changeType">
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="OPS">RT-PCR (OPS)</option>
                                    <option value="NPS" >RT-PCR (NPS)</option>
                                    <option value="OPS AND NPS" >RT-PCR (OPS and NPS)</option>
                                    <option value="ANTIGEN" >Antigen Test</option>
                                    <option value="ANTIBODY" >Antibody Test</option>
                                    <option value="OTHERS" >Others</option>
                                  </select>
                                </div>
                                @error('changeType')
									<small class="text-danger">{{$message}}</small>
								@enderror
                                <div id="ifAntigenOrOthers">
                                    <div class="form-group">
                                      <label for="reasonRemarks"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                      <input type="text" class="form-control" name="reasonRemarks" id="reasonRemarks">
                                    </div>
                                    <div id="ifAntigen">
                                        <div class="form-group">
                                            <label for="antigenKit"><span class="text-danger font-weight-bold">*</span>Antigen Kit</label>
                                            <input type="text" class="form-control" name="antigenKit" id="antigenKit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit" value="changetype">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="selectPatient" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New/Search CIF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                @if(session('modalmsg'))
                <div class="alert alert-danger" role="alert">
                    <form action="forms/singleExport/{{session('exist_id')}}" method="POST">
                    @csrf
                    {{session('modalmsg')}}<b>{{session('eName')}}</b>
                    <button type="submit" class="btn btn-link p-0"><i class="fas fa-file-excel mr-2"></i>Export to Excel</button>
                    @if(session('eType') == "ANTIGEN")
                    or
                    <a href="/forms/printAntigen/{{session('exist_id')}}/{{session('recordno')}}">Print Antigen Result</a>
                    @endif
                    </form>
                    <hr>
                    <p class="text-info">Philhealth: <u>{{session('philhealth')}}</u></p>
                    <p class="text-info">Date Collected / Type: <u>{{session('dateCollected')}} / {{session('eType')}}</u></p>
                    <p class="text-info">Result: <u>{{session('eResult')}}</u></p>
                    <p class="text-info">Attended: <u>{{session('attended')}}</u></p>
                    <p class="text-info">Encoded by / at: <u>{{session('encodedBy')}} / {{session('encodedDate')}}</u></p>
                    <hr>
                    To edit the existing CIF, click <a href="forms/{{session('exist_id')}}/edit">HERE</a>
                </div>
                <hr>
                @endif
                <div class="form-group">
                  <label for="id">Select Patient to Create or Search (If existing)</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" id="patient">
                        <option value="" disabled selected>Choose...</option>
                        @foreach ($records as $item)
                        <option value="/forms/{{$item->id}}/new">{{$item->lname.", ".$item->fname." ".$item->mname}} | {{$item->getAge()."/".strtoupper(substr($item->gender, 0,1))}} | {{date('m/d/Y', strtotime($item->bdate))}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).ready(function () {
        @if(session('modalmsg'))
        $('#selectPatient').modal('show');
        @endif

        $('#table_id').DataTable(
            {
        "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]],
        "order": [18, 'asc']
            }
        );

        $('#select_all').change(function() {
        var checkboxes = $(this).closest('form').find(':checkbox');
        checkboxes.prop('checked', $(this).is(':checked'));
        });

        $('#patient').selectize();
    });

    $('#changeTypeBtn').prop('disabled', true);
    $('#reschedBtn').prop('disabled', true);
    $('#exportBtn').prop('disabled', true);

    $('input:checkbox').click(function() {
        if ($(this).is(':checked')) {
            $('#changeTypeBtn').prop('disabled', false);
            $('#reschedBtn').prop('disabled', false);
            $('#exportBtn').prop("disabled", false);
        } else {
        if ($('.checks').filter(':checked').length < 1){
            $('#changeTypeBtn').prop('disabled', true);
            $('#reschedBtn').prop('disabled', true);
            $('#exportBtn').attr('disabled',true);}
        }
    });

    $('#changeType').change(function (e) { 
        e.preventDefault();
          if($(this).val() == 'ANTIGEN' || $(this).val() == 'OTHERS') {
            $('#ifAntigenOrOthers').show();
            $('#reasonRemarks').prop('required', true);

            if($(this).val() == 'ANTIGEN') {
                $('#ifAntigen').show();
                $('#antigenKit').prop('required', true);
            }
            else {
                $('#ifAntigen').hide();
                $('#antigenKit').prop('required', false);
            }
          }
          else {
            $('#ifAntigenOrOthers').hide();
            $('#reasonRemarks').prop('required', false);

            $('#ifAntigen').hide();
            $('#antigenKit').prop('required', false);
          }
    }).trigger('change');
</script>
@endsection