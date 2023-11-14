@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>
                    <div><b>Disease Checker List</b></div>
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filteropt">Filtering Options</button></div>
                </div>
            </div>
            <div class="card-body">
                @if(request()->input('db') && request()->input('year'))
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary btn-block">
                            <h4>DENGUE</h4>
                            <h5>123</h5>
                        </button>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                </div>
                @else

                @endif
                
            </div>
        </div>
    </div>
    
    <form action="" method="GET">
        <div class="modal fade" id="filteropt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filtering Options</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="db">Search in</label>
                          <select class="form-control" name="db" id="db">
                            <option value="OPD">OPD (Suspected Diseases List)</option>
                            <option value="PIDSR">PIDSR Database</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="year">Select Year</label>
                          <select class="form-control" name="year" id="year" required>
                            <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach(range(date('Y'), 2015) as $y)
                            <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="mw">Select Morbidity Week (Optional)</label>
                          <input type="number" min="1" max="53" class="form-control" name="mw" id="mw">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection