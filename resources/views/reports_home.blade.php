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
            <div class="card-header">Daily Report</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th colspan="2">For {{date('M d, Y')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Number of Patients Swabbed</td>
                                <td class="font-weight-bold">{{$list->where('testDateCollected1', date('Y-m-d'))->where('isPresentOnSwabDay', 1)->count()}}</td>
                            </tr>
                            <tr>
                                <td>Number of Patients not Present</td>
                                <td class="font-weight-bold">{{$list->where('testDateCollected1', date('Y-m-d'))->where('isPresentOnSwabDay', 0)->count()}}</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>TOTAL</td>
                                <td>{{$list->where('testDateCollected1', date('Y-m-d'))->count()}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dt_table5">
                        <thead>
                            <tr class="font-weight-bold text-primary">
                                <th colspan="6">List of Patients Swabbed Today</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Classification</th>
                                <th>Street</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('testDateCollected1', date('Y-m-d'))->where('isPresentOnSwabDay', 1) as $key => $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->pType}}</td>
                                <td>{{strtoupper($item->caseClassification)}}</td>
                                <td>{{$item->records->address_street}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dt_table6">
                        <thead>
                            <tr class="font-weight-bold text-primary">
                                <th colspan="5" style="vertical-align: middle;">List of Patients not Present</th>
                                <th colspan="1">
                                    <form action="{{route('report.makeAllSuspected')}}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger" type="submit">Make All Suspected</button>
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Classification</th>
                                <th>Street</th>
                                <th>Brgy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->where('testDateCollected1', date('Y-m-d'))->where('isPresentOnSwabDay', 0) as $key => $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                                <td>{{$item->pType}}</td>
                                <td>{{strtoupper($item->caseClassification)}}</td>
                                <td>{{$item->records->address_street}}</td>
                                <td>{{$item->records->address_brgy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
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
                                @if($list->where('records.address_brgy', $item->brgyName)->where('testDateCollected1', date('Y-m-d'))->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('isPresentOnSwabDay', 1)
                                        ->where('testDateCollected1', date('Y-m-d'))
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('isPresentOnSwabDay', 0)
                                        ->where('testDateCollected1', date('Y-m-d'))
                                        ->count()}}
                                    </td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('testDateCollected1', date('Y-m-d'))
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
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
                                <th colspan="4">Barangay Breakdown of Recorded PROBABLE Cases</th>
                            </tr>
                            <tr class="text-center">
                                <th>Barangay</th>
                                <th>Number of Recorded PROBABLE Cases</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($list->where('records.address_brgy', $item->brgyName)->where('caseClassification', 'Probable')->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Probable')
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
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
                                <th colspan="4">Barangay Breakdown of Recorded SUSPECTED Cases</th>
                            </tr>
                            <tr class="text-center">
                                <th>Barangay</th>
                                <th>Number of Recorded SUSPECTED Cases</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($list->where('records.address_brgy', $item->brgyName)->where('caseClassification', 'Suspect')->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Suspect')
                                        ->count()}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tr class="font-weight-bold text-center">
                                <td>TOTAL</td>
                                <td>{{$list
                                    ->where('caseClassification', 'Suspect')
                                    ->count()}}
                                </td>
                            </tr>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
                                <th colspan="4">Barangay Breakdown of Recorded CONFIRMED Cases</th>
                            </tr>
                            <tr class="text-center">
                                <th>Barangay</th>
                                <th>Number of Recorded CONFIRMED Cases</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($list->where('records.address_brgy', $item->brgyName)->where('caseClassification', 'Confirmed')->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
                                    <td>{{$list
                                        ->where('records.address_brgy', $item->brgyName)
                                        ->where('caseClassification', 'Confirmed')
                                        ->count()}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tr class="font-weight-bold text-center">
                                <td>TOTAL</td>
                                <td>{{$list
                                    ->where('caseClassification', 'Confirmed')
                                    ->count()}}
                                </td>
                            </tr>
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
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="font-weight-bold text-primary text-center">
                                <th colspan="4">Barangay Breakdown of Recorded NEGATIVE Cases</th>
                            </tr>
                            <tr class="text-center">
                                <th>Barangay</th>
                                <th>Number of Recorded NEGATIVE Cases</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy_list as $key => $item)
                                @if($list->where('records.address_brgy', $item->brgyName)->where('caseClassification', 'Non-COVID-19 Case')->count())
                                <tr class="text-center">
                                    <td>{{$item->brgyName}}</td>
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
                                    ->where('caseClassification', 'Non-COVID-19 Case')
                                    ->count()}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#dt_table1').DataTable();
            $('#dt_table2').DataTable();
            $('#dt_table3').DataTable();
            $('#dt_table4').DataTable();
            $('#dt_table5').DataTable();
            $('#dt_table6').DataTable();
            $('#dt_table7').DataTable({
                "ordering": false,
            });
        });
    </script>
@endsection