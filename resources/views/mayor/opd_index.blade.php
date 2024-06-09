@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>OPD Monitoring</b></div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" name="date" id="date" value="{{(request()->input('date')) ? request()->input('date') : date('Y-m-d')}}" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="submit"><i class="fas fa-calendar-alt mr-2"></i>Date Search</button>
                        </div>
                    </div>
                </form>
                <hr>
                @if($arr_list->count() != 0)
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name/Facility</th>
                            <th>Sent Home</th>
                            <th>Transfer to Hospital of Choice (THOC)</th>
                            <th>Home Againts Medical Advice (HAMA)</th>
                            <th>Admitted</th>
                            <th>TB-DOTS</th>
                            <th>Sent To Jail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr_list as $ind => $d)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>
                                <div><b>{{$d['name']}}</b></div>
                                <div><small>{{$d['dru_name']}}</small></div>
                            </td>
                            <td class="text-center">{{($d['sent_home'] != 0) ? $d['sent_home'] : ''}}</td>
                            <td class="text-center">{{($d['thoc'] != 0) ? $d['thoc'] : ''}}</td>
                            <td class="text-center">{{($d['hama'] != 0) ? $d['hama'] : ''}}</td>
                            <td class="text-center">{{($d['admitted'] != 0) ? $d['admitted'] : ''}}</td>
                            <td class="text-center">{{($d['tbdots'] != 0) ? $d['tbdots'] : ''}}</td>
                            <td class="text-center">{{($d['jail'] != 0) ? $d['jail'] : ''}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center">No Results found for {{date('M d, Y', strtotime($date))}}</p>
                @endif
            </div>
        </div>
    </div>
@endsection