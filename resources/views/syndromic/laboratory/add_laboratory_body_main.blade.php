@extends('layouts.app')

@section('content')
    <form action="{{route('syndromic_store_labresult', [$d->id, $case_code])}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>Add Laboratory Data - {{$case_code}}</b></div>
                <div class="card-body">
                    @if($case_code == 'Dengue')
                    <div class="form-group">
                      <label for="performed_ns1"><b class="text-danger">*</b>Dengue NS1</label>
                      <select class="form-control" name="performed_ns1" id="performed_ns1" required>
                        <option value="Y" {{(old('performed_ns1') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('performed_ns1') == 'N') ? 'selected' : ''}}>No</option>
                      </select>
                    </div>

                    <div id="ifDengueNs1Div" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ns1_date_collected"><b class="text-danger">*</b>Date Collected</label>
                                    <input type="date" class="form-control" name="ns1_date_collected" id="ns1_date_collected" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="ns1_result"><b class="text-danger">*</b>Result</label>
                                  <select class="form-control" name="ns1_result" id="ns1_result">
                                    <option value="" disabled {{(is_null(old('ns1_result'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="POSITIVE" {{(old('ns1_result') == 'POSITIVE') ? 'selected' : ''}}>Positive</option>
                                    <option value="NEGATIVE" {{(old('ns1_result') == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ns1_collected_by"><b class="text-danger">*</b>Collected by</label>
                                    <select class="form-control" name="ns1_collected_by" id="ns1_collected_by">
                                      <option value="" disabled {{(is_null(old('collected_by'))) ? 'selected' : ''}}>Choose...</option>
                                      @foreach($rmt_list as $rmt)
                                      <option value="{{$rmt->getNameWithPr()}};{{$rmt->job_position}};{{$rmt->prc_license_no}}">{{$rmt->getNameWithPr()}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="performed_igg"><b class="text-danger">*</b>Dengue IgG</label>
                        <select class="form-control" name="performed_igg" id="performed_igg" required>
                          <option value="" disabled {{(is_null(old('performed_igg'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('performed_igg') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('performed_igg') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div id="ifDengueIggDiv" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="igg_date_collected"><b class="text-danger">*</b>Date Collected</label>
                                    <input type="date" class="form-control" name="igg_date_collected" id="igg_date_collected" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="igg_result"><b class="text-danger">*</b>Result</label>
                                  <select class="form-control" name="igg_result" id="igg_result">
                                    <option value="" disabled {{(is_null(old('igg_result'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="POSITIVE" {{(old('igg_result') == 'POSITIVE') ? 'selected' : ''}}>Positive</option>
                                    <option value="NEGATIVE" {{(old('igg_result') == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="igg_collected_by"><b class="text-danger">*</b>Collected by</label>
                                    <select class="form-control" name="igg_collected_by" id="igg_collected_by">
                                      <option value="" disabled {{(is_null(old('collected_by'))) ? 'selected' : ''}}>Choose...</option>
                                      @foreach($rmt_list as $rmt)
                                      <option value="{{$rmt->getNameWithPr()}};{{$rmt->job_position}};{{$rmt->prc_license_no}}">{{$rmt->getNameWithPr()}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="form-group">
                        <label for="performed_igm"><b class="text-danger">*</b>Dengue IgM</label>
                        <select class="form-control" name="performed_igm" id="performed_igm" required>
                          <option value="" disabled {{(is_null(old('performed_igm'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('performed_igm') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('performed_igm') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div id="ifDengueIgmDiv" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="igm_date_collected"><b class="text-danger">*</b>Date Collected</label>
                                    <input type="date" class="form-control" name="igm_date_collected" id="igm_date_collected" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="igm_result"><b class="text-danger">*</b>Result</label>
                                  <select class="form-control" name="igm_result" id="igm_result">
                                    <option value="" disabled {{(is_null(old('igm_result'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="POSITIVE" {{(old('igm_result') == 'POSITIVE') ? 'selected' : ''}}>Positive</option>
                                    <option value="NEGATIVE" {{(old('igm_result') == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="igm_collected_by"><b class="text-danger">*</b>Collected by</label>
                                    <select class="form-control" name="igm_collected_by" id="igm_collected_by">
                                      <option value="" disabled {{(is_null(old('igm_collected_by'))) ? 'selected' : ''}}>Choose...</option>
                                      @foreach($rmt_list as $rmt)
                                      <option value="{{$rmt->getNameWithPr()}};{{$rmt->job_position}};{{$rmt->prc_license_no}}">{{$rmt->getNameWithPr()}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Save (CTRL + S)</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#submitBtn').trigger('click');
                $('#submitBtn').prop('disabled', true);
                setTimeout(function() {
                    $('#submitBtn').prop('disabled', false);
                }, 2000);
                return false;
            }
        });
    </script>

    @if($case_code == 'Dengue')
    <script>
        $('#performed_ns1').change(function (e) { 
            e.preventDefault();
            
            if($(this).val() == 'Y') {
                $('#ifDengueNs1Div').removeClass('d-none');

                $('#ns1_date_collected').prop('required', true);
                $('#ns1_result').prop('required', true);
                $('#ns1_collected_by').prop('required', true);
            }
            else {
                $('#ifDengueNs1Div').addClass('d-none');

                $('#ns1_date_collected').prop('required', false);
                $('#ns1_result').prop('required', false);
                $('#ns1_collected_by').prop('required', false);
            }
        }).trigger('change');

        $('#performed_igg').change(function (e) { 
            e.preventDefault();
            
            if($(this).val() == 'Y') {
                $('#ifDengueIggDiv').removeClass('d-none');

                $('#igg_date_collected').prop('required', true);
                $('#igg_result').prop('required', true);
                $('#igg_collected_by').prop('required', true);
            }
            else {
                $('#ifDengueIggDiv').addClass('d-none');

                $('#igg_date_collected').prop('required', false);
                $('#igg_result').prop('required', false);
                $('#igg_collected_by').prop('required', false);
            }
        }).trigger('change');
        
        $('#performed_igm').change(function (e) { 
            e.preventDefault();
            
            if($(this).val() == 'Y') {
                $('#ifDengueIgmDiv').removeClass('d-none');

                $('#igm_date_collected').prop('required', true);
                $('#igm_result').prop('required', true);
                $('#igm_collected_by').prop('required', true);
            }
            else {
                $('#ifDengueIgmDiv').addClass('d-none');

                $('#igm_date_collected').prop('required', false);
                $('#igm_result').prop('required', false);
                $('#igm_collected_by').prop('required', false);
            }
        }).trigger('change');
    </script>
    @endif
@endsection