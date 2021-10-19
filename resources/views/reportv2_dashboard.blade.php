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
        @if(auth()->user()->isBrgyAccount())
        <div class="alert alert-info text-center" role="alert">
            <strong>Note:</strong> Displaying results from BRGY. {{auth()->user()->brgy->brgyName}} Data ONLY.
        </div>
        @endif
        <div class="card border-danger mb-3">
            <div class="card-header font-weight-bold bg-danger text-white">List of Active Confirmed Patients (Total: {{number_format($activeconfirmed_count)}})</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>CIF ID</th>
                            <th>MM</th>
                            <th>MW</th>
                            <th>Date Reported</th>
                            <th>DRU</th>
                            <th>DRU Region</th>
                            <th>DRU Mun/City</th>
                            <th>Name</th>
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
                        @foreach($activeconfirmed_list as $item)
                        <tr>
                            <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                            <td class="text-center">{{$item->drunit}}</td>
                            <td class="text-center">{{$item->drregion}}</td>
                            <td class="text-center">{{$item->drprovince}}</td>
                            <td>{{$item->records->getName()}}</td>
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

        <div class="card border-success mb-3">
            <div class="card-header font-weight-bold bg-success text-white">List of Recovered Patients (Total: {{number_format($recovered_count)}})</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>CIF ID</th>
                            <th>MM</th>
                            <th>MW</th>
                            <th>Date Reported</th>
                            <th>DRU</th>
                            <th>DRU Region</th>
                            <th>DRU Mun/City</th>
                            <th>Name</th>
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
                        @foreach($recovered_list as $item)
                        <tr>
                            <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                            <td class="text-center">{{$item->drunit}}</td>
                            <td class="text-center">{{$item->drregion}}</td>
                            <td class="text-center">{{$item->drprovince}}</td>
                            <td>{{$item->records->getName()}}</td>
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

        <div class="card border-dark">
            <div class="card-header font-weight-bold bg-dark text-white">List of Death Patients (Total: {{number_format($death_count)}})</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>CIF ID</th>
                            <th>MM</th>
                            <th>MW</th>
                            <th>Date Reported</th>
                            <th>DRU</th>
                            <th>DRU Region</th>
                            <th>DRU Mun/City</th>
                            <th>Name</th>
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
                        @foreach($death_list as $item)
                        <tr>
                            <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                            <td class="text-center">{{$item->drunit}}</td>
                            <td class="text-center">{{$item->drregion}}</td>
                            <td class="text-center">{{$item->drprovince}}</td>
                            <td>{{$item->records->getName()}}</td>
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
        <hr>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr class="text-center bg-primary text-white font-weight-bold">
                    <th colspan="15">LIST OF CURRENTLY ADMITTED AT GENERAL TRIAS ISOLATION FACILITY</th>
                </tr>
                <tr class="text-center">
                    <th>CIF ID</th>
                    <th>MM</th>
                    <th>MW</th>
                    <th>Date Reported</th>
                    <th>DRU</th>
                    <th>DRU Region</th>
                    <th>DRU Mun/City</th>
                    <th>Name</th>
                    <th>Age / Sex</th>
                    <th>Birthdate</th>
                    <th>Street</th>
                    <th>Brgy</th>
                    <th>Case Severity</th>
                    <th>Date of Specimen Collection</th>
                    <th>Classification</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facility_list as $item)
                <tr>
                    <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                    <td class="text-center">{{$item->drunit}}</td>
                    <td class="text-center">{{$item->drregion}}</td>
                    <td class="text-center">{{$item->drprovince}}</td>
                    <td>{{$item->records->getName()}}</td>
                    <td class="text-center">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
                    <td class="text-center"><small>{{$item->records->address_street}}</small></td>
                    <td class="text-center">{{$item->records->address_brgy}}</td>
                    <td class="text-center">{{$item->healthStatus}}</td>
                    <td class="text-center">{{(!is_null($item->testDateCollected2)) ? date('m/d/Y', strtotime($item->testDateCollected2)) : date('m/d/Y', strtotime($item->testDateCollected1))}}</td>
                    <td class="text-center">{{$item->caseClassification}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr class="text-center bg-primary text-white font-weight-bold">
                    <th colspan="15">LIST OF HOME QUARANTINE</th>
                </tr>
                <tr class="text-center">
                    <th>CIF ID</th>
                    <th>MM</th>
                    <th>MW</th>
                    <th>Date Reported</th>
                    <th>DRU</th>
                    <th>DRU Region</th>
                    <th>DRU Mun/City</th>
                    <th>Name</th>
                    <th>Age / Sex</th>
                    <th>Birthdate</th>
                    <th>Street</th>
                    <th>Brgy</th>
                    <th>Case Severity</th>
                    <th>Date of Specimen Collection</th>
                    <th>Classification</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hq_list as $item)
                <tr>
                    <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                    <td class="text-center">{{$item->drunit}}</td>
                    <td class="text-center">{{$item->drregion}}</td>
                    <td class="text-center">{{$item->drprovince}}</td>
                    <td>{{$item->records->getName()}}</td>
                    <td class="text-center">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
                    <td class="text-center"><small>{{$item->records->address_street}}</small></td>
                    <td class="text-center">{{$item->records->address_brgy}}</td>
                    <td class="text-center">{{$item->healthStatus}}</td>
                    <td class="text-center">{{(!is_null($item->testDateCollected2)) ? date('m/d/Y', strtotime($item->testDateCollected2)) : date('m/d/Y', strtotime($item->testDateCollected1))}}</td>
                    <td class="text-center">{{$item->caseClassification}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-bordered table-hover">
            
            <thead class="thead-light">
                <tr class="text-center bg-primary text-white font-weight-bold">
                    <th colspan="16">LIST OF ADMITTED IN OTHER HOSPITAL/ISOLATION FACILITY</th>
                </tr>
                <tr class="text-center">
                    <th>CIF ID</th>
                    <th>MM</th>
                    <th>MW</th>
                    <th>Date Reported</th>
                    <th>DRU</th>
                    <th>DRU Region</th>
                    <th>DRU Mun/City</th>
                    <th>Name</th>
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
                @foreach($otherfacility_list as $item)
                <tr>
                    <td scope="row" class="text-center"><a href="/forms/{{$item->id}}/edit">{{$item->id}}</a></td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('W', strtotime($item->created_at))}}</td>
                    <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                    <td class="text-center">{{$item->drunit}}</td>
                    <td class="text-center">{{$item->drregion}}</td>
                    <td class="text-center">{{$item->drprovince}}</td>
                    <td>{{$item->records->getName()}}</td>
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

    <script>
        $(document).ready(function () {
            $('.table').DataTable({
                dom: 'fBrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
            $('#loading').fadeOut();
        });
    </script>
@endsection