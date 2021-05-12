@extends('layouts.app_pdf')

@section('content')
    @php
    $n = 0;
    @endphp
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif;">
        @while($n+1 <= $list->count())
        <div class="text-center">
            <h6 class="font-weight-bold">PROVINCE OF CAVITE</h6>
            <h6>Cavite De La Salle Medical Health Science Institute COVID19 Diagnostic Center</h6>
            <h6>Dasmariñas City, Cavite</h6>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color: #ffc000;">
                    <th class="text-center" colspan="8">LINELIST OF SPECIMENS REFERRED FOR COVID-19 TESTING</th>
                </tr>
                <tr>
                    <th colspan="2">Disease Reporting Unit (Hospital/Agency)</th>
                    <th colspan="2" class="text-center">CITY HEALTH OFFICE - GENERAL TRIAS</th>
                    <th colspan="2">Date of Specimen Shipment (mm-dd-yyy)</th>
                    <th colspan="2" class="text-center">{{date('m/d/Y', strtotime($details->laSalleDateAndTimeShipment))}}</th>
                </tr>
                <tr>
                    <th colspan="2">Referring Physician</th>
                    <th colspan="2" class="text-center">{{$details->laSallePhysician}}</th>
                    <th colspan="2">Time of Specimen Shipment</th>
                    <th colspan="2" class="text-center">{{date('h:i', strtotime($details->laSalleDateAndTimeShipment))}} ( {{(date('A', strtotime($details->laSalleDateAndTimeShipment)) == 'AM') ? 'X' : ''}} ) AM   ( {{(date('A', strtotime($details->laSalleDateAndTimeShipment)) == 'PM') ? 'X' : ''}} ) PM</th>
                </tr>
                <tr>
                    <th colspan="2">Contact Person</th>
                    <th colspan="2" class="text-center">{{$details->contactPerson}}</th>
                    <th colspan="2">Official E-mail Address</th>
                    <th colspan="2" class="text-center">{{$details->email}}</th>
                </tr>
                <tr>
                    <th colspan="2">Telephone Number</th>
                    <th colspan="2" class="text-center">{{$details->contactTelephone}}</th>
                    <th colspan="2">Mobile Number</th>
                    <th colspan="2" class="text-center">{{$details->contactMobile}}</th>
                </tr>
                <tr style="background-color: #ffc000;" class="text-center">
                    <th>No.</th>
                    <th>Name of Patient</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Date of Specimen Collection</th>
                    <th>Time of Specimen Collection</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<=9;$i++)
                    @if($n != $list->count())
                    <tr class="text-center">
                        <td scope="row">{{$n+1}}</td>
                        <td>{{$list[$n]->records->lname.", ".$list[$n]->records->fname." "}} {{(!is_null($list[$n]->records->mname)) ? $list[$n]->records->mname : ''}}</td>
                        <td>{{date('m/d/Y', strtotime($list[$n]->records->bdate))}}</td>
                        <td></td>
                        <td>({{($list[$n]->records->gender == "MALE") ? 'X' : ' '}}) Male ({{($list[$n]->records->gender == "FEMALE") ? 'X' : ' '}}) Female</td>
                        <td>{{date('m/d/Y', strtotime($list[$n]->dateAndTimeCollected))}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                    $n++;
                    @endphp
                    @else
                    <tr class="text-center">
                        <td scope="row">N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    </tr>
                    @endif
                @endfor
            </tbody>
        </table>
        @endwhile
    </div>
@endsection