@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header font-weight-bold">Pa-Swab List @if(!request()->input('q'))(Current Pending Total: {{number_format($list->total())}})@endif</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('paswab.view')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Name / Schedule Code / Referral Code">
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(request()->input('q'))
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}. <a href="{{route('paswab.view')}}">GO BACK</a>
                </div>
                @endif
                <form action="{{route('paswab.options')}}" method="POST">
                    @csrf
                    <div>
                        <button type="button" class="btn btn-success my-3" data-toggle="modal" data-target="#bulkapprove" id="bulkbtn"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i> Bulk Approve Data</button>
                        <button type="button" class="btn btn-danger my-3" data-toggle="modal" data-target="#bulkreject" id="bulkrejectbtn"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i> Bulk Reject Data</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="paswabtbl">
                            <thead class="text-center thead-light">
                                <tr>
                                    <th></th>
                                    <th style="vertical-align: middle;"><input type="checkbox" class="checks mx-2" name="" id="select_all"></th>
                                    <th style="vertical-align: middle;">Date Submitted</th>
                                    <th style="vertical-align: middle;">Name</th>
                                    <th style="vertical-align: middle;">Philhealth</th>
                                    <th style="vertical-align: middle;">Mobile</th>
                                    <th style="vertical-align: middle;">Birthdate</th>
                                    <th style="vertical-align: middle;">Age / Gender</th>
                                    <th style="vertical-align: middle;">Pregnant / LMP</th>
                                    <th style="vertical-align: middle;">Client Type</th>
                                    <th style="vertical-align: middle;">Vaccinated</th>
                                    <th style="vertical-align: middle;">Have Symptoms</th>
                                    <th style="vertical-align: middle;">Date Onset of Illness</th>
                                    <th style="vertical-align: middle;">Date Interviewed</th>
                                    <th style="vertical-align: middle;">Address</th>
                                    <th style="vertical-align: middle;">Referral Code</th>
                                    <th style="vertical-align: middle;">Schedule Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $item)
                                    <tr>
                                        <td></td>
                                        <td class="text-center" style="vertical-align: middle;"><input type="checkbox" class="checks" name="bulkIDList[]" id="" value="{{$item->id}}"></td>
                                        <td class="text-center" style="vertical-align: middle;"><small>{{date('m/d/Y h:i:s A', strtotime($item->created_at))}}</small></td>
                                        <td style="vertical-align: middle;">
                                            <a href="/forms/paswab/view/{{$item->id}}" class="btn btn-link text-left">
                                                @if($item->isNewRecord == 1)<span class="badge badge-danger">New</span>@endif
                                                {{$item->getName()}}
                                                @if($item->isPregnant == 1)<span class="badge badge-info">PREGNANT</span>@endif
                                                @if($item->isForHospitalization == 1)<span class="badge badge-secondary">HOSP.</span>@endif
                                                @if($item->forAntigen == 1)<span class="badge badge-success">ANTIGEN</span>@endif
                                            </a>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->philhealth)) ? $item->philhealth : 'N/A'}}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{$item->mobile}}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->bdate))}}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{$item->getAge()." / ".substr($item->gender,0,1)}}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{($item->isPregnant == 1) ? 'YES / '.date('m/d/Y', strtotime($item->ifPregnantLMP)).' - '.$item->diff4Humans($item->ifPregnantLMP) : 'NO'}}</td>
                                        <td class="text-center" style="vertical-align: middle;"><span class="{{($item->getPatientType() == 'FOR TRAVEL') ? 'font-weight-bold text-danger' : ''}}">{{$item->getPatientType()}}</span> <small>{{(!is_null($item->expoDateLastCont) && $item->pType == 'CLOSE CONTACT') ? "(".date('m/d/Y - D', strtotime($item->expoDateLastCont)).", ".$item->diff4Humans($item->expoDateLastCont).")" : ''}}</small></td>
                                        <td class="text-center" style="vertical-align: middle;"><small>{{(!is_null($item->vaccinationDate1)) ? 'YES ('.$item->vaccinationName1.') - ' : 'NO'}}{{(!is_null($item->vaccinationDate1)) ? (!is_null($item->vaccinationDate2)) ? '2nd Dose' : '1st Dose' : ''}}</small></td>
                                        <td class="text-center {{!is_null($item->SAS) ? 'text-danger font-weight-bold' : ''}}" style="vertical-align: middle;">{{!is_null($item->SAS) ? 'YES' : 'NONE'}}</td>
                                        <td class="text-center {{(!is_null($item->dateOnsetOfIllness)) ? 'text-danger font-weight-bold' : ''}}" style="vertical-align: middle;">{{(!is_null($item->dateOnsetOfIllness)) ? date('m/d/Y (D)', strtotime($item->dateOnsetOfIllness)).' - '.$item->diff4Humans($item->dateOnsetOfIllness) : 'N/A'}}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->interviewDate))}}</td>
                                        <td style="vertical-align: middle;"><small>{{$item->getAddress()}}</small></td>
                                        <td class="text-center" style="vertical-align: middle;"><small>{{(!is_null($item->linkCode)) ? $item->linkCode : 'N/A'}}</small></td>
                                        <td class="text-center" style="vertical-align: middle;">{{$item->majikCode}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="bulkapprove" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-success font-weight-bold">Bulk Approve Data</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                      <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date of Swab Collection</label>
                                      <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{date('Y-01-01')}}" max="{{date('Y-12-31')}}" value="{{old('testDateCollected1')}}">
                                    </div>
                                    <div class="form-group">
                                      <label for="testType1"><span class="text-danger font-weight-bold">*</span>Type of Test</label>
                                      <select class="form-control" name="testType1" id="testType1">
                                        <option value="" disabled {{(is_null(old('testType1'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="OPS" {{(old('testType1') == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                        <option value="NPS" {{(old('testType1') == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                        <option value="OPS AND NPS" {{(old('testType1') == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                        <option value="ANTIGEN" {{(old('testType1') == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                        <option value="ANTIBODY" {{(old('testType1') == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                        <option value="OTHERS" {{(old('testType1') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                      </select>
                                    </div>
                                    <div id="divTypeOthers1">
                                        <div class="form-group">
                                            <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                            <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1')}}">
                                        </div>
                                        <div id="ifAntigen1">
                                            <div class="form-group">
                                                <label for="antigenKit1"><span class="text-danger font-weight-bold">*</span>Antigen Kit</label>
                                                <input type="text" class="form-control" name="antigenKit1" id="antigenKit1" value="{{old('antigenKit1')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        Note: If the selected Pa-Swab Request/s contains 'For Antigen', the test type will still remain as is after accepted. Name of Antigen Test Kit and Reason for Antigen Test will be written based on default values in system settings.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="bulkApprove" class="btn btn-success">Accept</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="bulkreject" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger font-weight-bold">Bulk Reject Data</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                      <label for="rejectReason"><span class="text-danger font-weight-bold">*</span>State Reason for Rejection</label>
                                      <textarea class="form-control" name="rejectReason" id="rejectReason" rows="3"></textarea>
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        Note: By Rejecting a Pa-Swab Request/s, their details will still be saved in our system for later use but it is not included in the masterlist (Meaning, it will not be counted in the official list).
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="bulkReject" class="btn btn-danger">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#bulkbtn').prop('disabled', true);
        $('#bulkrejectbtn').prop('disabled', true);

        $('input:checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#bulkrejectbtn').prop('disabled', false);
                $('#bulkbtn').prop('disabled', false);
            }
            else {
                if ($('.checks').filter(':checked').length < 1 || $('#select_all').prop('checked') == false) {
                    $('#bulkbtn').prop('disabled', true);
                    $('#bulkrejectbtn').prop('disabled', true);
                }
            }
        });

        $('#bulkbtn').click(function (e) { 
            $('#testDateCollected1').prop('required', true);
            $('#testType1').prop('required', true);
            $('#rejectReason').prop('required', false);
        });

        $('#bulkrejectbtn').click(function (e) { 
            $('#testDateCollected1').prop('required', false);
            $('#testType1').prop('required', false);
            $('#rejectReason').prop('required', true);
        });

        $('#paswabtbl').dataTable({
            dom: 'tr',
            responsive: {
                details: {
                    type: 'inline',
                    target: 'tr'
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0,
            }],
            fixedHeader: true,
            "ordering": false,
        });

        $('#select_all').change(function() {
            var checkboxes = $(this).closest('form').find(':checkbox');
            checkboxes.prop('checked', $(this).is(':checked'));
        });

        $('#testType1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                $('#divTypeOthers1').show();
                $('#testTypeOtherRemarks1').prop('required', true);
                if($(this).val() == 'ANTIGEN') {
                    $('#ifAntigen1').show();
                    $('#antigenKit1').prop('required', true);
                }
                else {
                    $('#ifAntigen1').hide();
                    $('#antigenKit1').prop('required', false);
                }
            }
            else {
                $('#divTypeOthers1').hide();
                $('#testTypeOtherRemarks1').empty();
                $('#testTypeOtherRemarks1').prop('required', false);

                $('#ifAntigen1').hide();
                $('#antigenKit1').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection