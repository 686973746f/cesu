@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>FWRI Report</b></div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">for Year</span>
                        </div>
                        <select class="custom-select" name="forYear" id="forYear" required>
                            <option disabled {{(is_null(request()->input('forYear'))) ? 'selected' : ''}}>Select Year...</option>
                            @foreach(range(date('Y'), 2023) as $y)
                            <option value="{{$y}}" {{(request()->input('forYear') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h2>Total Firework-Related Injury (FWRI) Cases (Gen. Trias)</h2></div>
                            <div class="card-body">
                                <h3><b>{{$get_total}}</b></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h2>Male</h2></div>
                            <div class="card-body">
                                <h3><b>{{$get_male}}</b></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h2>Female</h2></div>
                            <div class="card-body">
                                <h3><b>{{$get_female}}</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-header"><h3>Cases Graph</h3></div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-header"><h3>Place of Occurrence</h3></div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h3>Type of Involvement</h3></div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h3>Nature of Injury</h3></div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header"><h3>Type of Fireworks Injury</h3></div>
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection