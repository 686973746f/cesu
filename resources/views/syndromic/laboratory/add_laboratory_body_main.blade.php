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
                                    <label for="date_collected"><b class="text-danger">*</b>Date Collected</label>
                                    <input type="date" class="form-control" name="date_collected" id="date_collected" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="result"><b class="text-danger">*</b>Result</label>
                                  <select class="form-control" name="result" id="result">
                                    <option value="" disabled {{(is_null(old('result'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="POSITIVE" {{(old('result') == 'POSITIVE') ? 'selected' : ''}}>Positive</option>
                                    <option value="NEGATIVE" {{(old('result') == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="collected_by"><b class="text-danger">*</b>Collected by</label>
                                    <select class="form-control" name="collected_by" id="collected_by">
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
                    <div id="ifDengueIggDiv">

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
                    <div id="ifDengueIgmDiv">
                        
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </form>

    @if($case_code == 'Dengue')
    <script>
        $('#performed_ns1').change(function (e) { 
            e.preventDefault();
            
            if($(this).val() == 'Y') {
                $('#ifDengueNs1Div').removeClass('d-none');

                $('#date_collected').prop('required', true);
                $('#result').prop('required', true);
                $('#collected_by').prop('required', true);
            }
            else {
                $('#ifDengueNs1Div').addClass('d-none');

                $('#date_collected').prop('required', false);
                $('#result').prop('required', false);
                $('#collected_by').prop('required', false);
            }
        }).trigger('change');
    </script>
    @endif
@endsection