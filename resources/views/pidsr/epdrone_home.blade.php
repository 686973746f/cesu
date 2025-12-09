@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <div><b>Validated Encoded Epidemic-prone Diseases</b></div>
                    <div class="font-weight-bold text-success"><h3>Year: {{(request()->input('year')) ? request()->input('year') : date('Y')}}</h3></div>
                </div>
                <div><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#filterModal">Filter</button></div>
            </div>
        </div>
        <div class="card-body text-center">
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#downloadCsv">Download CSV Templates</button>
            <a href="" class="btn btn-success btn-lg btn-block">View For Uploading</a>
            <hr>
            @include('pidsr.epdrone_body')
        </div>
    </div>
</div>

<form action="{{route('pidsr_epdrone_downloadcsv')}}" method="POST">
        @csrf
        <div class="modal fade" id="downloadCsv" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download CSV Template</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="disease"><b class="text-danger">*</b>Select Disease</label>
                            <select class="form-control" name="disease" id="disease" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(\App\Http\Controllers\PIDSRController::listReportableDiseasesBackEnd()->where('edcs_importable', true) as $disease)
                                <option value="{{$disease['value']}}">{{$disease['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                    <input type="date" class="form-control" name="startDate" id="startDate" min="2025-01-01" max="{{date('Y-m-t')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                    <input type="date" class="form-control" name="endDate" id="endDate" min="2020-01-01" max="{{date('Y-m-t')}}" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="convert_flat" id="convert_flat" value="Y" checked>Download as Flat File <small>(This will make the address code as readable text form)</small></label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<form action="" method="GET">
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Year</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year"><b class="text-danger">*</b>Select Year</label>
                        <select class="form-control" name="year" id="year" required>
                            <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach(range(date('Y'), 2015) as $y)
                            <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quarter">Quarter (Optional)</label>
                        <select class="form-control" name="quarter" id="quarter">
                            <option value="">N/A</option>
                            <option value="1ST" {{(request()->input('quarter') == '1ST') ? 'selected' : ''}}>1st Quarter</option>
                            <option value="2ND" {{(request()->input('quarter') == '2ND') ? 'selected' : ''}}>2nd Quarter</option>
                            <option value="3RD" {{(request()->input('quarter') == '3RD') ? 'selected' : ''}}>3rd Quarter</option>
                            <option value="4TH" {{(request()->input('quarter') == '4TH') ? 'selected' : ''}}>4th Quarter</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection