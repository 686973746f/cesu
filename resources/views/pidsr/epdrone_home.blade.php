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
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#filterModal">Change Year</button></div>
            </div>
        </div>
        <div class="card-body text-center">
            @include('pidsr.epdrone_body')
        </div>
    </div>
</div>

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
                            <option disabled {{(is_null(request()->input('quarter'))) ? 'selected' : ''}}>Choose...</option>
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