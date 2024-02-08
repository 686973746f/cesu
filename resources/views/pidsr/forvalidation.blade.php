@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>For Validation</b> (Count: {{count($list)}})</div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Filter by Year</span>
                    </div>
                    <select class="custom-select" name="year" id="year" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year...</option>
                        @foreach(range(date('Y'), 2023) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age/Sex</th>
                        <th>Streetpurok</th>
                        <th>Barangay</th>
                        <th>Case</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $l)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td><a href="{{route('pidsr_casechecker_edit', [$l['case_name'], $l['epi_id']])}}?fromVerifier=1">{{$l['name']}}</a></td>
                        <td class="text-center">{{$l['age']}}/{{$l['sex']}}</td>
                        <td class="{{($l['streetpurok'] != 'N/A') ? '' : 'font-weight-bold text-danger'}}">{{$l['streetpurok']}}</td>
                        <td class="text-center">{{$l['brgy']}}</td>
                        <td class="text-center">{{$l['case_name']}}</td>
                        <td class="text-center">{{$l['timestamp']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection