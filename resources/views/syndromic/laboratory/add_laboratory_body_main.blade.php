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
                      <label for="performed_ns1">Dengue NS1</label>
                      <select class="form-control" name="performed_ns1" id="performed_ns1" required>
                        <option value="" disabled {{(is_null(old('performed_ns1'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('performed_ns1') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('performed_ns1') == 'N') ? 'selected' : ''}}>No</option>
                      </select>
                    </div>

                    <div id="ifDengueNs1Div">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_collected"><b class="text-danger">*</b>Date Collected</label>
                                    <input type="text" class="form-control" name="date_collected" id="date_collected" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="result"><b class="text-danger">*</b>Result</label>
                                  <select class="form-control" name="result" id="result" required>
                                    <option value="" disabled {{(is_null(old('result'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="POSITIVE" {{(old('result') == 'POSITIVE') ? 'selected' : ''}}>Positive</option>
                                    <option value="NEGATIVE" {{(old('result') == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="result"><b class="text-danger">*</b>Collected by</label>
                                    <select class="form-control" name="result" id="result" required>
                                      <option value="" disabled {{(is_null(old('result'))) ? 'selected' : ''}}>Choose...</option>
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
                        <label for="performed_igg">Dengue IgG</label>
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
                        <label for="performed_igm">Dengue IgM</label>
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
    @endif
@endsection