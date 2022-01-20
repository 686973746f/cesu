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
            <div class="card-header font-weight-bold">{{$list_name}}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>MM</th>
                                <th>MW</th>
                                <th>Date Reported</th>
                                <th>DRU</th>
                                <th>DRU Region</th>
                                <th>DRU Mun/City</th>
                                <th>Name / ID</th>
                                <th>Age / Sex</th>
                                <th>Birthdate</th>
                                <th>Street</th>
                                <th>Brgy</th>
                                <th>Case Severity</th>
                                <th>Date of Specimen Collection</th>
                                <th>Classification</th>
                                <th>Quarantine Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $item)
                            <tr>
                                <td class="text-center">{{date('m/d/Y', strtotime($item->morbidityMonth))}}</td>
                                <td class="text-center">{{date('W', strtotime($item->morbidityMonth))}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                                <td class="text-center">{{$item->drunit}}</td>
                                <td class="text-center">{{$item->drregion}}</td>
                                <td class="text-center">{{$item->drprovince}}</td>
                                <td><a href="/forms/{{$item->id}}/edit">{{$item->records->getName()}} (#{{$item->id}})</a></td>
                                <td class="text-center">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
                                <td class="text-center"><small>{{$item->records->address_street}}</small></td>
                                <td class="text-center">{{$item->records->address_brgy}}</td>
                                <td class="text-center">{{$item->healthStatus}}</td>
                                <td class="text-center">{{(!is_null($item->testDateCollected2)) ? date('m/d/Y', strtotime($item->testDateCollected2)) : date('m/d/Y', strtotime($item->testDateCollected1))}}</td>
                                <td class="text-center">{{$item->caseClassification}}</td>
                                <td class="text-center">{{$item->getQuarantineStatus()}}</td>
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