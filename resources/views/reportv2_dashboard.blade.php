@extends('layouts.app')

@section('content')
    <style>
        #loading {
            position: fixed;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            background-color: #fff;
            z-index: 99;
        }
    </style>
    <div id="loading">
        <div class="text-center">
            <i class="fas fa-circle-notch fa-spin fa-5x my-3"></i>
            <h3>Loading Data. Please Wait...</h3>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">{{$list_name}} | Total: {{number_format($list_count)}}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th style="vertical-align: middle;">MM/MW <small>(Date Encoded)</small></th>
                                <th style="vertical-align: middle;">Date Reported</th>
                                <th style="vertical-align: middle;">DRU</th>
                                <th style="vertical-align: middle;">DRU Region & Mun/City</th>
                                <th style="vertical-align: middle;">Patient Type</th>
                                <th style="vertical-align: middle;">Name / ID</th>
                                <th style="vertical-align: middle;">Age</th>
                                <th style="vertical-align: middle;">Gender</th>
                                <th style="vertical-align: middle;">Birthdate</th>
                                <th style="vertical-align: middle;">House #</th>
                                <th style="vertical-align: middle;">Street</th>
                                <th style="vertical-align: middle;">Brgy</th>
                                <th style="vertical-align: middle;">Occupation</th>
                                <th style="vertical-align: middle;">Workplace City</th>
                                <th style="vertical-align: middle;">Workplace Province</th>
                                <th style="vertical-align: middle;">Case Severity</th>
                                <th style="vertical-align: middle;">Date of Specimen Collection</th>
                                <th style="vertical-align: middle;">Classification</th>
                                <th style="vertical-align: middle;">Quarantine Status</th>
                                <th style="vertical-align: middle;">Vaccine</th>
                                @if(request()->input('getOption') == 1 || request()->input('getOption') == 2 || request()->input('getOption') == 6)
                                <th style="vertical-align: middle;">Date of Possible Recovery</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $item)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->morbidityMonth))}} / W{{date('W', strtotime($item->morbidityMonth))}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y (D)', strtotime($item->dateReported))}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->drunit}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->drregion.' '.$item->drprovince}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->getType()}}</td>
                                <td style="vertical-align: middle;"><a href="/forms/{{$item->id}}/edit">{{$item->records->getName()}} <small>(#{{$item->id}})</small></a></td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->records->getAge()}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{substr($item->records->gender,0,1)}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
                                <td class="text-center" style="vertical-align: middle;"><small>{{$item->records->address_houseno}}</small></td>
                                <td class="text-center" style="vertical-align: middle;"><small>{{$item->records->address_street}}</small></td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->records->address_brgy}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{($item->records->hasOccupation == 1) ? $item->records->occupation : 'N/A'}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{($item->records->hasOccupation == 1) ? $item->records->occupation_city : 'N/A'}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{($item->records->hasOccupation == 1) ? $item->records->occupation_province : 'N/A'}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->healthStatus}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->testDateCollected2)) ? date('m/d/Y (D)', strtotime($item->testDateCollected2)) : date('m/d/Y (D)', strtotime($item->testDateCollected1))}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->caseClassification}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->getQuarantineStatus()}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$item->records->showVaxInfo()}}</td>
                                @if(request()->input('getOption') == 1 || request()->input('getOption') == 2 || request()->input('getOption') == 6)
                                <td class="text-center" style="vertical-align: middle;">{{$item->getPossibleRecoveryDate()}}</td>
                                @endif
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
            $('.table').DataTable({
                dom: 'fQBrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
            $('#loading').fadeOut();
        });
    </script>
@endsection