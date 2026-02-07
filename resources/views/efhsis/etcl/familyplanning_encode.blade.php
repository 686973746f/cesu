@extends('layouts.app')

@section('content')
@if($mode == 'EDIT')
<form action="{{route('etcl_familyplanning_update', $d->id)}}" method="POST">
@else
<form action="{{route('etcl_familyplanning_store', $patient->id)}}" method="POST">
@endif
@csrf
<input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="container">
        <div class="card">
            <div class="card-header">
                @if($mode == 'EDIT')
                <b>Edit Family Planning (ID: {{ $d->id }})</b>
                @else
                <b>New Family Planning</b>
                @endif
            </div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_date"><b class="text-danger">*</b>Date of Registration</label>
                            <input type="date" class="form-control" name="registration_date" id="registration_date" value="{{old('registration_date', $d->registration_date)}}" max="{{date('Y-m-d')}}" {{($mode == 'EDIT') ? 'disabled' : 'required'}}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Family Serial No.</label>
                          <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' : $patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Name of Child / Age</label>
                            <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->getName().' / '.$d->patient->getAge() : $patient->getName().' / '.$patient->getAge() }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Date of Birth</label>
                            <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->patient->bdate)->format('m/d/Y') : Carbon\Carbon::parse($patient->bdate)->format('m/d/Y') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Complete Address</label>
                            <textarea class="form-control" rows="3" disabled>{{ ($mode == 'EDIT') ? $d->patient->getFullAddress() : $patient->getFullAddress() }}</textarea>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_type"><b class="text-danger">*</b>Type of Client</label>
                            <select class="form-control" name="client_type" id="client_type" {{($mode == 'EDIT') ? 'disabled' : 'required'}}>
                              <option value="" disabled {{ old('client_type', $d->client_type) ? '' : 'selected' }}>Choose...</option>
                              <option value="NA" {{ old('client_type', $d->client_type) == 'NA' ? 'selected' : '' }}>New Acceptors</option>
                              <option value="CU" {{ old('client_type', $d->client_type) == 'CU' ? 'selected' : '' }}>Current Users</option>
                              <option value="OA" {{ old('client_type', $d->client_type) == 'OA' ? 'selected' : '' }}>Other Acceptors</option>
                              <option value="CU-CM" {{ old('client_type', $d->client_type) == 'CU-CM' ? 'selected' : '' }}>Changing Method</option>
                              <option value="CU-CC" {{ old('client_type', $d->client_type) == 'CU-CC' ? 'selected' : '' }}>Changing Clinic</option>
                              <option value="CU-RS" {{ old('client_type', $d->client_type) == 'CU-RS' ? 'selected' : '' }}>Restarter</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="source"><b class="text-danger">*</b>Source</label>
                            <select class="form-control" name="source" id="source" required>
                              <option value="" disabled {{ old('source', $d->source) ? '' : 'selected' }}>Choose...</option>
                              <option value="PUBLIC" {{ old('source', $d->source) == 'PUBLIC' ? 'selected' : '' }}>Public</option>
                              <option value="PRIVATE" {{ old('source', $d->source) == 'PRIVATE' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="previous_method"><b class="text-danger">*</b>Previous Method</label>
                            <select class="form-control" name="previous_method" id="previous_method" required>
                                <option value="" disabled {{ old('previous_method', $d->previous_method) ? '' : 'selected' }}>Choose...</option>
                                <option value="BTL" {{ old('previous_method', $d->previous_method) == 'BTL' ? 'selected' : '' }}>Bilateral Tubal Ligation</option>
                                <option value="NSV" {{ old('previous_method', $d->previous_method) == 'NSV' ? 'selected' : '' }}>No-Scalpel Vasectomy</option>
                                <option value="CON" {{ old('previous_method', $d->previous_method) == 'CON' ? 'selected' : '' }}>Condom</option>
                                <option value="PILLS-POP" {{ old('previous_method', $d->previous_method) == 'PILLS-POP' ? 'selected' : '' }}>Progestin Only Pills</option>
                                <option value="PILLS-COC" {{ old('previous_method', $d->previous_method) == 'PILLS-COC' ? 'selected' : '' }}>Combined Oral Contraceptive Pills</option>
                                <option value="INJ" {{ old('previous_method', $d->previous_method) == 'INJ' ? 'selected' : '' }}>DMPA or CIC</option>
                                <option value="IMP-I" {{ old('previous_method', $d->previous_method) == 'IMP-I' ? 'selected' : '' }}>Single rod sub-dermal Implant (Interval)</option>
                                <option value="IMP-PP" {{ old('previous_method', $d->previous_method) == 'IMP-PP' ? 'selected' : '' }}>Single rod sub-dermal Implant (Postpartum)</option>
                                <option value="IUD-I" {{ old('previous_method', $d->previous_method) == 'IUD-I' ? 'selected' : '' }}>IUD Interval</option>
                                <option value="IUD-PP" {{ old('previous_method', $d->previous_method) == 'IUD-PP' ? 'selected' : '' }}>IUD Postpartum</option>
                                <option value="NFP-LAM" {{ old('previous_method', $d->previous_method) == 'NFP-LAM' ? 'selected' : '' }}>Lactational Amenorrhea Method</option>
                                <option value="NFP-BBT" {{ old('previous_method', $d->previous_method) == 'NFP-BBT' ? 'selected' : '' }}>Basal Body Temperature</option>
                                <option value="NFP-CMM" {{ old('previous_method', $d->previous_method) == 'NFP-CMM' ? 'selected' : '' }}>Cervical Mucus Method</option>
                                <option value="NFP-STM" {{ old('previous_method', $d->previous_method) == 'NFP-STM' ? 'selected' : '' }}>Symptothermal Method</option>
                                <option value="NFP-SDM" {{ old('previous_method', $d->previous_method) == 'NFP-SDM' ? 'selected' : '' }}>Standard Days Method</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if($mode != 'EDIT')
                
                @else
                <hr>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Follow-up Visits</b></div>
                            <div>
                                @if($d->visits->isEmpty())
                                <div class="text-center">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#firstVisitModal">
                                        Add First Visit
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($d->visits->isEmpty())
                        <div class="alert alert-info" role="alert">
                            No follow-up visits recorded.
                        </div>
                        @else

                        @foreach($d->visits as $visit)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6>Visit #{{ $loop->remaining + 1 }}</h6>
                                        <h6><b>Method:</b> {{ $visit->method_used }} | <b>Type:</b> {{ $visit->client_type }}</h6>
                                        <h6><b>Estimated Visit Date:</b> {{ Carbon\Carbon::parse($visit->visit_date_estimated)->format('F d, Y') }}</h6>
                                        @if($visit->visit_date_actual)
                                        <h6><b>Actual Visit Date:</b> {{ Carbon\Carbon::parse($visit->visit_date_actual)->format('F d, Y') }}</h6>
                                        @endif
                                        @if($visit->dropout_date)
                                        <h6><b class="text-danger">Drop-out Date:</b> {{ Carbon\Carbon::parse($visit->dropout_date)->format('F d, Y') }}</h6>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div><h6><b>Status:</b> {{ $visit->status }}</h6></div>
                                        <div>
                                            @if($loop->first && $visit->status == 'PENDING')
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nextVisitModal">
                                                Update
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endif
                <div class="form-group mt-3">
                    <label for="remarks">Remarks/Actions Taken</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" value="{{old('remarks', $d->remarks)}}">
                </div>
                <hr>
                <div class="form-group">
                    <label for="system_remarks">System Remarks (Optional)</label>
                    <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks', $d->system_remarks)}}</textarea>
                </div>
                @if($mode != 'EDIT')
                <div class="alert alert-info" role="alert">
                    To encode visits, please save the record first.
                </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" id="submitBtn" class="btn btn-success btn-block" {{($d->is_locked == 'Y') ? 'disabled' : ''}}>
                    @if($mode == 'EDIT')
                    Update (CTRL + S)
                    @else
                    Save (CTRL + S)
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>

@if($d->visits->isEmpty() && $mode == 'EDIT')
<form action="{{ route('etcl_familyplanning_first_visit', $d->id) }}" method="POST">
    @csrf
    <input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="modal fade" id="firstVisitModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create First Visit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="method"><b class="text-danger">*</b>Select Method</label>
                        <select class="form-control" name="method" id="method" required>
                            <option value="" disabled {{ old('method', $d->method) ? '' : 'selected' }}>Choose...</option>
                            <option value="BTL" {{ old('method', $d->method) == 'BTL' ? 'selected' : '' }}>Bilateral Tubal Ligation</option>
                            <option value="NSV" {{ old('method', $d->method) == 'NSV' ? 'selected' : '' }}>No-Scalpel Vasectomy</option>
                            <option value="CON" {{ old('method', $d->method) == 'CON' ? 'selected' : '' }}>Condom</option>
                            <option value="PILLS-POP" {{ old('method', $d->method) == 'PILLS-POP' ? 'selected' : '' }}>Progestin Only Pills</option>
                            <option value="PILLS-COC" {{ old('method', $d->method) == 'PILLS-COC' ? 'selected' : '' }}>Combined Oral Contraceptive Pills</option>
                            <option value="INJ" {{ old('method', $d->method) == 'INJ' ? 'selected' : '' }}>DMPA or CIC</option>
                            <option value="IMP-I" {{ old('method', $d->method) == 'IMP-I' ? 'selected' : '' }}>Single rod sub-dermal Implant (Interval)</option>
                            <option value="IMP-PP" {{ old('method', $d->method) == 'IMP-PP' ? 'selected' : '' }}>Single rod sub-dermal Implant (Postpartum)</option>
                            <option value="IUD-I" {{ old('method', $d->method) == 'IUD-I' ? 'selected' : '' }}>IUD Interval</option>
                            <option value="IUD-PP" {{ old('method', $d->method) == 'IUD-PP' ? 'selected' : '' }}>IUD Postpartum</option>
                            <option value="NFP-LAM" {{ old('method', $d->method) == 'NFP-LAM' ? 'selected' : '' }}>Lactational Amenorrhea Method</option>
                            <option value="NFP-BBT" {{ old('method', $d->method) == 'NFP-BBT' ? 'selected' : '' }}>Basal Body Temperature</option>
                            <option value="NFP-CMM" {{ old('method', $d->method) == 'NFP-CMM' ? 'selected' : '' }}>Cervical Mucus Method</option>
                            <option value="NFP-STM" {{ old('method', $d->method) == 'NFP-STM' ? 'selected' : '' }}>Symptothermal Method</option>
                            <option value="NFP-SDM" {{ old('method', $d->method) == 'NFP-SDM' ? 'selected' : '' }}>Standard Days Method</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="visit_date_actual"><b class="text-danger">*</b>Actual Date of Visit</label>
                        <input type="date" class="form-control" name="visit_date_actual" id="visit_date_actual" max="{{date('Y-m-d')}}" value="{{old('visit_date_actual')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@elseif($mode == 'EDIT' && $d->latestVisit->status == 'PENDING')

<form action="{{ route('etcl_familyplanning_process_next_visit', $d->id) }}" method="POST">
    @csrf
    <input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="modal fade" id="nextVisitModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Next Visit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                @if($d->latestVisit->ifEligibleForUpdate())
                <div class="modal-body">
                    <div class="form-group">
                        <label for=""><b class="text-danger">*</b>Method Used</label>
                        <input type="text" class="form-control" value="{{ $d->latestVisit->method_used }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="visit_date_estimated"><b class="text-danger">*</b>Estimated Date of Visit</label>
                        <input type="text" class="form-control" value="{{ Carbon\Carbon::parse($d->latestVisit->visit_date_estimated)->format('F d, Y') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status"><b class="text-danger">*</b>Status</label>
                        <select class="form-control" name="status" id="update_status" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="DONE">Done</option>
                            <option value="DROP-OUT">Drop-out</option>
                        </select>
                    </div> 
                    <div class="d-none" id="done_div">
                        <div class="form-group">
                            <label for="visit_date_actual"><b class="text-danger">*</b>Actual Date of Visit</label>
                            <input type="date" class="form-control" name="visit_date_actual" id="update_visit_date_actual" max="{{date('Y-m-d')}}" value="{{old('visit_date_actual')}}">
                        </div>
                    </div>
                    <div class="d-none" id="dropout_div">
                        <div class="form-group">
                            <label for="dropout_date"><b class="text-danger">*</b>Drop-out Date</label>
                            <input type="date" class="form-control" name="dropout_date" id="update_dropout_date" max="{{date('Y-m-d')}}" value="{{old('dropout_date')}}">
                        </div>
                        <div class="form-group">
                            <label for="dropout_reason"><b class="text-danger">*</b>Drop-out Reason</label>
                            <select class="form-control" name="dropout_reason" id="update_dropout_reason">
                                <option value="" disabled selected>Choose...</option>
                                <option value="A">Pregnant</option>
                                <option value="B">Desire to become pregnant</option>
                                <option value="C">Medical complications</option>
                                <option value="D">Fear of side effects</option>
                                <option value="E">Changed Clinic</option>
                                <option value="F">Husband disapproves</option>
                                <option value="G">Menopause</option>
                                <option value="H">Lost or moved out of the area or residence</option>
                                <option value="I">Failed to get supply</option>
                                <option value="J">Change Method</option>
                                <option value="K">Underwent Hysterectomy</option>
                                <option value="L">Underwent Bilateral Salpingo-oophorectomy</option>
                                <option value="M">No FP Commodity</option>
                                <option value="N">Unknown</option>
                                <option value="O">Age out for BTL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
                @else
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <b>Warning:</b> The next visit can only be updated within 7 days before the estimated visit date.
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
@endif
<script>
    $('#client_type').change(function (e) { 
        e.preventDefault();
        $('#previous_method').prop('disabled', false);
        $('#previous_method').prop('required', true);
        
        if($(this).val() == 'NA') {
            $('#previous_method').prop('disabled', true);
            $('#previous_method').prop('required', false);
            $('#previous_method').val('');
        }
    }).trigger('change');

    $('#update_status').change(function (e) { 
        e.preventDefault();
        $('#done_div').addClass('d-none');
        $('#dropout_div').addClass('d-none');

        $('#update_visit_date_actual').prop('required', false);
        $('#update_dropout_date').prop('required', false);
        $('#update_dropout_reason').prop('required', false);

        if($(this).val() == 'DONE') {
            $('#done_div').removeClass('d-none');
            $('#update_visit_date_actual').prop('required', true);
        }
        else if($(this).val() == 'DROP-OUT') {
            $('#dropout_div').removeClass('d-none');
            $('#update_dropout_date').prop('required', true);
            $('#update_dropout_reason').prop('required', true);
        }
    });
</script>
@endsection