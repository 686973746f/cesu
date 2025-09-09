@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Claim No. {{$d->id}}</b> - {{$d->patient->getName()}}</div>
                    <div></div>
                </div>
                
            </div>
            <form action="{{route('abtc_financial_processticket', $d->id)}}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    @if($d->ics_claims_status != 'PAID')
                    <div class="form-group">
                        @if($d->ics_claims_status == 'REQUEST_CLAIMED')
                        <label for="ics_claims_status"><b class="text-danger">*</b>Select Claim Status</label>
                        @else
                        <label for="ics_claims_status"><b class="text-danger">*</b>Update Claim Status</label>
                        @endif
                      <select class="form-control" name="ics_claims_status" id="ics_claims_status" required>
                        <option value="" disabled {{(old('ics_claims_status', $d->ics_claims_status) == 'ENCODING') ? 'selected' : ''}}>Choose...</option>
                        @if($d->ics_claims_status == 'REQUEST_CLAIMED')
                        <option value="FOR UPLOADING" {{(old('ics_claims_status', $d->ics_claims_status) == 'FOR UPLOADING') ? 'selected' : ''}}>For Uploading (Unsubmitted Claim)</option>
                        <option value="PROCESSING" {{(old('ics_claims_status', $d->ics_claims_status) == 'PROCESSING') ? 'selected' : ''}}>Processing (Unpaid Claim)</option>
                        <option value="RTH" {{(old('ics_claims_status', $d->ics_claims_status) == 'RTH') ? 'selected' : ''}}>RTH/For Compliance</option>
                        <option value="DENIED" {{(old('ics_claims_status', $d->ics_claims_status) == 'DENIED') ? 'selected' : ''}}>Denied</option>
                        <option value="PAID" {{(old('ics_claims_status', $d->ics_claims_status) == 'PAID') ? 'selected' : ''}}>Paid</option>
                        @elseif($d->ics_claims_status == 'FOR UPLOADING')
                        <option value="PROCESSING" {{(old('ics_claims_status', $d->ics_claims_status) == 'PROCESSING') ? 'selected' : ''}}>Processing (Unpaid Claim)</option>
                        @elseif($d->ics_claims_status == 'PROCESSING')
                        <option value="RTH" {{(old('ics_claims_status', $d->ics_claims_status) == 'RTH') ? 'selected' : ''}}>RTH/For Compliance</option>
                        <option value="DENIED" {{(old('ics_claims_status', $d->ics_claims_status) == 'DENIED') ? 'selected' : ''}}>Denied</option>
                        <option value="PAID" {{(old('ics_claims_status', $d->ics_claims_status) == 'PAID') ? 'selected' : ''}}>Paid</option>
                        @elseif($d->ics_claims_status == 'RTH')
                        <option value="PROCESSING/RTH" {{(old('ics_claims_status', $d->ics_claims_status) == 'PROCESSING/RTH') ? 'selected' : ''}}>Resubmit</option>
                        @elseif($d->ics_claims_status == 'PROCESSING/RTH')
                        <option value="PROCESSING/PROTEST" {{(old('ics_claims_status', $d->ics_claims_status) == 'PROCESSING/PROTEST') ? 'selected' : ''}}>Denied - Tag as Protest Claim</option>
                        <option value="PAID" {{(old('ics_claims_status', $d->ics_claims_status) == 'PAID') ? 'selected' : ''}}>Paid</option>
                        @elseif($d->ics_claims_status == 'DENIED')
                        <option value="PROCESSING/PROTEST" {{(old('ics_claims_status', $d->ics_claims_status) == 'PROCESSING/PROTEST') ? 'selected' : ''}}>Tag as Protest Claim</option>
                        @endif
                      </select>
                    </div>
                    @else
                    <h4>Claim Status: <b class="text-success">PAID</b></h4>
                    @endif

                    <div id="processing_div" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="ics_transmittalno"><b class="text-danger">*</b>Transmittal No.</label>
                                  <input type="text" class="form-control" name="ics_transmittalno" id="ics_transmittalno" value="{{old('ics_transmittalno', $d->ics_transmittalno)}}" style="text-transform: uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="ics_claims_seriesno"><b class="text-danger">*</b>Claim Series No.</label>
                                  <input type="text" class="form-control" name="ics_claims_seriesno" id="ics_claims_seriesno" value="{{old('ics_claims_seriesno', $d->ics_claims_seriesno)}}" style="text-transform: uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="paid_div" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="rvs1"><b class="text-danger">*</b>RVS</label>
                                  <input type="text" class="form-control" name="rvs1" id="rvs1" value="{{old('rvs1', $d->rvs1)}}" style="text-transform: uppercase">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="ics_claim_amount"><b class="text-danger">*</b>Claim Amount</label>
                                  <input type="number" class="form-control" name="ics_claim_amount" id="ics_claim_amount" value="{{old('ics_claim_amount', $d->ics_claim_amount)}}" style="text-transform: uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="ics_claims_remarks">Remarks</label>
                      <textarea class="form-control" name="ics_claims_remarks" id="ics_claims_remarks" rows="3">{{old('ics_claims_remarks', $d->ics_claims_remarks)}}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" name="btn" value="submit" {{($d->ics_claims_status == 'PAID') ? 'disabled' : ''}}>Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#ics_claims_status').change(function (e) { 
            e.preventDefault();
            $('#processing_div').addClass('d-none');
            $('#paid_div').addClass('d-none');
            $('#ics_transmittalno').prop('required', false);
            $('#ics_claims_seriesno').prop('required', false);
            $('#rvs1').prop('required', false);
            $('#ics_claim_amount').prop('required', false);

            if($(this).val() == 'PROCESSING') {
                $('#processing_div').removeClass('d-none');
                $('#ics_transmittalno').prop('required', true);
                $('#ics_claims_seriesno').prop('required', true);
            }
            else if($(this).val() == 'PAID') {
                $('#processing_div').removeClass('d-none');
                $('#ics_transmittalno').prop('required', true);
                $('#ics_claims_seriesno').prop('required', true);

                $('#paid_div').removeClass('d-none');
                $('#rvs1').prop('required', true);
                $('#ics_claim_amount').prop('required', true);

                $('#rvs1').val('90375');
                $('#ics_claim_amount').val('5850');
            }
        }).trigger('change');
    </script>
@endsection