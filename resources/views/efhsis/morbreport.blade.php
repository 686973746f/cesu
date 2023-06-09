@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Report</b></div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Select Options first to Proceed</span>
                    </div>
                    <select class="custom-select" name="disease" id="disease" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Disease...</option>
                        <option value="COVID">COVID-19</option>
                        <option value="Animalbite">Animal Bite</option>
                        <option value="COVID">Animal Bite</option>
                    </select>
                    <select class="custom-select" name="year" id="year" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year...</option>
                        @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select d-none" name="month" id="month">
                        <option disabled {{(is_null(request()->input('month'))) ? 'selected' : ''}}>Select Month</option>
                        <option value="01" {{(request()->input('month') == '01') ? 'selected' : ''}}>JANUARY</option>
                        <option value="02" {{(request()->input('month') == '02') ? 'selected' : ''}}>FEBRUARY</option>
                        <option value="03" {{(request()->input('month') == '03') ? 'selected' : ''}}>MARCH</option>
                        <option value="04" {{(request()->input('month') == '04') ? 'selected' : ''}}>APRIL</option>
                        <option value="05" {{(request()->input('month') == '05') ? 'selected' : ''}}>MAY</option>
                        <option value="06" {{(request()->input('month') == '06') ? 'selected' : ''}}>JUNE</option>
                        <option value="07" {{(request()->input('month') == '07') ? 'selected' : ''}}>JULY</option>
                        <option value="08" {{(request()->input('month') == '08') ? 'selected' : ''}}>AUGUST</option>
                        <option value="09" {{(request()->input('month') == '09') ? 'selected' : ''}}>SEPTEMBER</option>
                        <option value="10" {{(request()->input('month') == '10') ? 'selected' : ''}}>OCTOBER</option>
                        <option value="11" {{(request()->input('month') == '11') ? 'selected' : ''}}>NOVEMBER</option>
                        <option value="12" {{(request()->input('month') == '12') ? 'selected' : ''}}>DECEMBER</option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
    </div>
</div>
@endsection