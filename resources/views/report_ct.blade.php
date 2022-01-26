@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Contact Tracing Report</div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="3">SUMMARY</th>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>Active</th>
                            <th>Total Contact Traced</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr_summary as $arr)
                        <tr>
                            <td>{{date('m/d/Y', strtotime($arr['date']))}}</td>
                            <td>{{$arr['numActive']}}</td>
                            <td>{{$arr['numCT']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(!request()->input('getDate'))
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Total Contact Traced as of {{date('m/d/Y')}}</th>
                            <th>Total Active Case as of {{date('m/d/Y')}}</th>
                            <th>Ratio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$grandTotalContactTraced}}</td>
                            <td>{{$activeCasesCount}}</td>
                            <td>1:{{ceil($grandTotalContactTraced / $activeCasesCount)}}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
                <hr>
                <form action="{{route('report.ct.index')}}" method="GET">
                    <label for="">Generate Report on Date</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" name="getDate" id="getDate" value="{{(!request()->input('getDate')) ? date('Y-m-d') : request()->input('getDate')}}" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}">
                        <div class="input-group-append">
                          <button class="btn btn-outline-success" type="submit">Search</button>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="7">{{(!request()->input('getDate')) ? date('m/d/Y') : date('m/d/Y', strtotime(request()->input('getDate')))}}</th>
                        </tr>
                        <tr>
                            <th>BARANGAY</th>
                            <th>PRIMARY</th>
                            <th>SECONDARY</th>
                            <th>TERTIARY</th>
                            <th>SUSPECTED</th>
                            <th>PROBABLE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $i)
                        <tr>
                            <td>{{$i['brgyName']}}</td>
                            <td>{{$i['primaryCount']}}</td>
                            <td>{{$i['secondaryCount']}}</td>
                            <td>{{$i['tertiaryCount']}}</td>
                            <td>{{$i['suspectedCount']}}</td>
                            <td>{{$i['probableCount']}}</td>
                            <td class="font-weight-bold">{{($i['primaryCount'] + $i['secondaryCount'] + $i['tertiaryCount'] + $i['suspectedCount'] + $i['probableCount'])}}</td>
                        </tr>
                        @php
                        $totalPrimary += $i['primaryCount'];
                        $totalSecondary += $i['secondaryCount'];
                        $totalTertiary += $i['tertiaryCount'];
                        $totalSuspected += $i['suspectedCount'];
                        $totalProbable += $i['probableCount'];

                        $grandTotal = ($totalPrimary + $totalSecondary + $totalTertiary + $totalSuspected + $totalProbable);
                        @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>{{$totalPrimary}}</td>
                            <td>{{$totalSecondary}}</td>
                            <td>{{$totalTertiary}}</td>
                            <td>{{$totalSuspected}}</td>
                            <td>{{$totalProbable}}</td>
                            <td>{{$grandTotal}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection