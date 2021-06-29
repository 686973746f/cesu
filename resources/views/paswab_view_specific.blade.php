@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">View Pa-swab Record</div>
            <div class="card-body">

            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptmodal"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i> Approve</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectmodal"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i> Reject</button>
            </div>
        </div>
    </div>
    
    <form action="/forms/paswab/{{$data->id}}/approve" method="POST">
        @csrf
        <div class="modal fade" id="acceptmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Accept Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                            <select name="interviewerName" id="interviewerName" required>
                                <option value="" disabled {{(empty(old('interviewerName'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach($interviewers as $key => $interviewer)
                                    <option value="{{$interviewer->lname.", ".$interviewer->fname}}" {{(old('interviewerName') == $interviewer->lname.", ".$interviewer->fname) ? 'selected' : ''}}>{{$interviewer->lname.", ".$interviewer->fname." ".$interviewer->mname}}{{(!is_null($interviewer->brgy_id)) ? " (".$interviewer->brgy->brgyName.")" : ''}}{{(!is_null($interviewer->desc)) ? " - ".$interviewer->desc : ""}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date of Swab Collection</label>
                            <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{date('Y-01-01')}}" value="{{old('testDateCollected1')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="testType1"><span class="text-danger font-weight-bold">*</span>Type of Test</label>
                            <select class="form-control" name="testType1" id="testType1" required>
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="/forms/paswab/{{$data->id}}/reject" method="POST">
        @csrf
        <div class="modal fade" id="rejectmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="reason"><span class="text-danger font-weight-bold">*</span>Specify Reason for Rejection</label>
                          <input type="text" class="form-control" name="reason" id="reason" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i>Reject Record</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#interviewerName').selectize();

        $('#testType1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                $('#divTypeOthers1').show();
                $('#testTypeOtherRemarks1').prop('required', true);
            }
            else {
                $('#divTypeOthers1').hide();
                $('#testTypeOtherRemarks1').empty();
                $('#testTypeOtherRemarks1').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection