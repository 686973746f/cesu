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
                <div class="card-header">Export to Excel</div>
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
            <div class="card-header">Daily Report</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr class="bg-light">
                                <th colspan="2">For {{date('M d, Y')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Number of Patients Swabbed</td>
                                <td class="font-weight-bold text-center">{{$listToday
                                ->where('isPresentOnSwabDay', 1)
                                ->count()}}</td>
                            </tr>
                            <tr>
                                <td>Number of Patients not Present</td>
                                <td class="font-weight-bold text-center">{{$notPresent->count()}}</td>
                            </tr>
                            <tr class="font-weight-bold bg-light">
                                <td>TOTAL</td>
                                <td class="font-weight-bold text-center">{{$listToday->count()}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
            </div>
        </div>
        <div class="card">
            <div class="card-header">Report</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
                                <th colspan="5">Barangay Breakdown of Reported Cases</th>
                            </tr>
                            <tr class="text-center">
                                <th>Barangay</th>
                                <th>Probable</th>
                                <th>Suspect</th>
                                <th>Confirmed</th>
                                <th>Non-COVID 19</th>
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
                            <tr class="font-weight-bold text-center">
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
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table" id="dt_table1">
                        <thead>
                            <tr>
                                <th colspan="3" class="font-weight-bold text-primary">Total Number of Recorded PROBABLE Cases: {{$list->where('caseClassification', 'Probable')->count()}}</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('caseClassification', 'Probable') as $key => $item)
                            <tr>
                                <td scope="row">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table" id="dt_table2">
                        <thead>
                            <tr>
                                <th colspan="3" class="font-weight-bold text-primary">Total Number of Recorded SUSPECTED Cases: {{$list->where('caseClassification', 'Suspect')->count()}}</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('caseClassification', 'Suspect') as $key => $item)
                            <tr>
                                <td scope="row">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table" id="dt_table3">
                        <thead>
                            <tr>
                                <th colspan="3" class="font-weight-bold text-primary">Total Number of Recorded CONFIRMED Cases: {{$list->where('caseClassification', 'Confirmed')->count()}}</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('caseClassification', 'Confirmed') as $key => $item)
                            <tr>
                                <td scope="row">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table" id="dt_table4">
                        <thead>
                            <tr>
                                <th colspan="3" class="font-weight-bold text-primary">Total Number of Recorded NEGATIVE Cases: {{$list->where('caseClassification', 'Non-COVID-19 Case')->count()}}</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('caseClassification', 'Non-COVID-19 Case') as $key => $item)
                            <tr>
                                <td scope="row">{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#dt_table1').DataTable({
                responsive: true,
            });
            $('#dt_table2').DataTable({
                responsive: true,
            });
            $('#dt_table3').DataTable({
                responsive: true,
            });
            $('#dt_table4').DataTable({
                responsive: true,
            });
            $('#dt_table5').DataTable({
                responsive: true,
            });
            $('#dt_table6').DataTable({
                responsive: true,
            });
            $('#dt_table7').DataTable({
                "ordering": false,
                responsive: true,
            });
        });
    </script>
@endsection