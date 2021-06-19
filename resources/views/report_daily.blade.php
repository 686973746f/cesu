@extends('layouts.app')

@section('content') 
    <div class="container" style="font-family: Arial, Helvetica, sans-serif;">
        @if(session('status'))
            <div class="alert alert-{{session('statustype')}}" role="alert">
                {{session('status')}}
            </div>
            <hr>
        @endif 
        <form action="{{route('report.export')}}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Export to Excel</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="eStartDate">From</label>
                              <input type="date" class="form-control" name="eStartDate" id="eStartDate" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eEndDate">To</label>
                                <input type="date" class="form-control" name="eEndDate" id="eEndDate" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="rType">Report Type</label>
                      <select class="form-control" name="rType" id="rType" required>
                        <option value="" selected disabled>Choose...</option>
                        <option value="DOH">DOH Report Format</option>
                        <option value="CIF">CIF Report Format</option>
                      </select>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </div>
        </form>
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Daily Report</div>
            <div class="card-body">
                @if($listToday->count())
                <div id="chart" style="height: 300px;"></div>
                <script>
                    const chart = new Chartisan({
                      el: '#chart',
                      url: "@chart('daily_swab_chart')",
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
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center bg-light">
                                <th colspan="4">Number of Patients per Barangay for {{date('M d, Y')}}</th>
                            </tr>
                            <tr class="text-center">
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
                                    <td>{{$listToday
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->count()}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
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
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Confirmed')
                                        ->count()}}
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
            $('#dt_table6').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection