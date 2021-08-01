@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Situational Report v2</div>
            <div class="card-body">
                <a href="{{route('report.situationalv2.print')}}" class="btn btn-primary btn-block">Print Excel Data for PowerPoint</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Barangay Breakdown of Cases</div>
            <div class="card-body">
                <table class="table table-bordered text-center" id="brgy_breakdown">
                    <thead class="thead-light">
                        <tr>
                            <th>Barangay</th>
                            <th>Confirmed</th>
                            <th>Active</th>
                            <th>Death</th>
                            <th>Recoveries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brgyList as $brgy)
                        <tr>
                            <td class="font-weight-bold">{{$brgy->brgyName}}</td>
                            <td>{{$formsList
                            ->where('records.address_brgy', $brgy->brgyName)
                            ->where('outcomeCondition', 'Active')
                            ->where('caseClassification', 'Confirmed')
                            ->count()}}</td>
                            <td>{{$formsList
                            ->where('records.address_brgy', $brgy->brgyName)
                            ->where('outcomeCondition', 'Active')
                            ->count()}}</td>
                            <td class="text-danger">{{$formsList
                            ->where('records.address_brgy', $brgy->brgyName)
                            ->where('outcomeCondition', 'Died')
                            ->count()}}</td>
                            <td class="text-success">{{$formsList
                            ->where('records.address_brgy', $brgy->brgyName)
                            ->where('outcomeCondition', 'Recovered')
                            ->count()}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="font-weight-bold">AREAS OUTSIDE GEN. TRIAS</td>
                            <td>{{$formsList
                            ->where('records.address_city', '!=', 'GENERAL TRIAS')
                            ->where('outcomeCondition', 'Active')
                            ->where('caseClassification', 'Confirmed')
                            ->count()}}</td>
                            <td>{{$formsList
                            ->where('records.address_city', '!=', 'GENERAL TRIAS')
                            ->where('outcomeCondition', 'Active')
                            ->count()}}</td>
                            <td class="text-danger">{{$formsList
                            ->where('records.address_city', '!=', 'GENERAL TRIAS')
                            ->where('outcomeCondition', 'Died')
                            ->count()}}</td>
                            <td class="text-success">{{$formsList
                            ->where('records.address_city', '!=', 'GENERAL TRIAS')
                            ->where('outcomeCondition', 'Recovered')
                            ->count()}}</td>
                        </tr>
                    </tbody>
                    <tfoot class="font-weight-bold bg-light">
                        <tr>
                            <td>TOTAL</td>
                            <td>{{$formsList
                            ->where('outcomeCondition', 'Active')
                            ->where('caseClassification', 'Confirmed')
                            ->count()}}</td>
                            <td>{{$formsList
                            ->where('outcomeCondition', 'Active')
                            ->count()}}</td>
                            <td class="text-danger">{{$formsList
                            ->where('outcomeCondition', 'Died')
                            ->count()}}</td>
                            <td class="text-success">{{$formsList
                            ->where('outcomeCondition', 'Recovered')
                            ->count()}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header font-weight-bold">Barangay Active Cases Clustering</div>
            <div class="card-body">
                <table class="table table-bordered text-center" id="clustertbl">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;">Date Reported</th>
                            <th style="vertical-align: middle;">Patient Type</th>
                            <th style="vertical-align: middle;">Name</th>
                            <th style="vertical-align: middle;">Age/Sex</th>
                            <th style="vertical-align: middle;">Street</th>
                            <th style="vertical-align: middle;">Barangay</th>
                            <th style="vertical-align: middle;">City/Municipality</th>
                            <th style="vertical-align: middle;">Province</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formsList
                        ->where('outcomeCondition', 'Active')
                        ->where('caseClassification', 'Confirmed') as $item)
                        @php
                        if($item->pType == "PROBABLE") {
                            $pTypeStr = "SUSPECTED";
                        }
                        else if($item->pType == 'CLOSE CONTACT') {
                            $pTypeStr = "CLOSE CONTACT";
                        }
                        else {
                            $pTypeStr = "NON-COVID CASE";
                        }
                        @endphp
                        <tr>
                            <td>{{date('m/d/Y', strtotime($item->interviewDate))}}</td>
                            <td>{{$pTypeStr}}</td>
                            <td>{{$item->records->getName()}}</td>
                            <td>{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
                            <td>{{$item->records->address_street}}</td>
                            <td>BRGY. {{$item->records->address_brgy}}</td>
                            <td>{{$item->records->address_city}}</td>
                            <td>{{$item->records->address_province}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#brgy_breakdown').DataTable({
                dom: 'Bfrt',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]],
                "bSort" : false
            });

            $('#clustertbl').DataTable({
                order: [[7, 'asc'],[6, 'asc'],[5, 'asc'],[4, 'asc']],
                rowGroup: {
                    startRender: null,
                    startRender: function ( rows, group ) {
                        return '<b>' + group +' ('+rows.count()+')</b>';
                    },
                    dataSrc: [7,6,5]
                },
                dom: 'Bfrt',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]],
            });
        });
    </script>
@endsection