@extends('layouts.app')

@section('content') 
    <div class="container" style="font-family: Arial, Helvetica, sans-serif;">
        @if(session('status'))
            <div class="alert alert-{{session('statustype')}}" role="alert">
                {{session('status')}}
            </div>
            <hr>
        @endif 
        
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Daily Report</div>
            <div class="card-body">
                @if($listToday->count())
                <div id="chart" style="height: 300px;"></div>
                <script>
                    const chart = new Chartisan({
                      el: '#chart',
                      url: "{{route('charts.daily_swab_chart')}}",
                      hooks: new ChartisanHooks()
                      .title('Number of Swabbed patients for {{date("M d, Y")}}')
                      .pieColors(['green', 'red'])
                      .datasets('pie')
                    })
                </script>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dt_table5">
                        <thead>
                            <tr class="font-weight-bold text-primary bg-light">
                                <th colspan="6">List of Patients Swabbed Today</th>
                            </tr>
                            <tr class="text-center ">
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Classification</th>
                                <th>Street</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listToday->where('isPresentOnSwabDay', 1) as $key => $item)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td class="text-center">{{$item->pType}}</td>
                                <td class="text-center">{{strtoupper($item->caseClassification)}}</td>
                                <td class="text-center">{{$item->records->address_street}}</td>
                                <td class="text-center">{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dt_table6">
                        <thead>
                            <tr class="font-weight-bold text-primary bg-light">
                                <th colspan="5" style="vertical-align: middle;">List of Patients not Present</th>
                                <th colspan="1">
                                    <form action="{{route('report.makeAllSuspected')}}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger" type="submit">Mark all absent as "Suspected"</button>
                                    </form>
                                </th>
                            </tr>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Classification</th>
                                <th>Street</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notPresent as $key => $item)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td class="text-center">{{$item->pType}}</td>
                                <td class="text-center">{{strtoupper($item->caseClassification)}}</td>
                                <td class="text-center">{{$item->records->address_street}}</td>
                                <td class="text-center">{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dailyBrgy">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center bg-light">
                                <th colspan="4">Number of Patients per Barangay Scheduled for Swab Today ({{date('M d, Y')}})</th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th>Barangay</th>
                                <th>Present</th>
                                <th>Absent / Pending</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($listToday->where('records.address_brgy', $item->brgyName)->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$listToday
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('isPresentOnSwabDay', 1)
                                        ->count()}}
                                    </td>
                                    <td>{{$notPresent
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->count()}}
                                    </td>
                                    <td class="font-weight-bold">{{$listToday
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->count()}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tfoot class="text-center font-weight-bold bg-light">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{$listToday->where('isPresentOnSwabDay', 1)->count()}}</td>
                                    <td>{{$notPresent->count()}}</td>
                                    <td>{{$listToday->count()}}</td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">There are no records scheduled for swab today.</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header font-weight-bold">Report</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="brgy_breakdown">
                        <thead>
                            <tr class="font-weight-bold text-primary bg-light text-center">
                                <th colspan="5">Barangay Breakdown of Reported Cases</th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th>Barangay</th>
                                <th>Probable</th>
                                <th>Suspect</th>
                                <th>Confirmed / Positive</th>
                                <th>Non-COVID 19 / Negative</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($item->brgyName != "MEDICARE")
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Probable')
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Suspect')
                                        ->count()}}
                                    </td>
                                    <td>
                                        <a href="/report/clustering/{{$item->city_id}}/{{$item->id}}">
                                        {{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Confirmed')
                                        ->count()}}</a>
                                    </td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Non-COVID-19 Case')
                                        ->count()}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tfoot>
                                <tr class="font-weight-bold text-center bg-light">
                                    <td>TOTAL</td>
                                    <td>{{$list
                                        ->where('caseClassification', 'Probable')
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('caseClassification', 'Suspect')
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('caseClassification', 'Confirmed')
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('caseClassification', 'Non-COVID-19 Case')
                                        ->count()}}
                                    </td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#dt_table5').DataTable({
                responsive: true,
            });

            $('#brgy_breakdown').DataTable({
                responsive: true,
                dom: 'tr',
                paging: false,
            });

            $('#dailyBrgy').DataTable({
                responsive: true,
                dom: 'tr',
                paging: false,
            });

            $('#dt_table6').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection