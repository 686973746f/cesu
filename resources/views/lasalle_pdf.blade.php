@extends('layouts.app_pdf')
<style>
    @page { margin: 0; }
    body { margin: 0; }
</style>
@section('content')
    @php
    $n = 0;
    if($size == 'legal') {
        $fsize = '65%';
    }
    else {
        $fsize = '55%';
    }
    @endphp
    @while($n+1 <= $list->count())
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif;font-size: {{$fsize}}; page-break-after: {{($n+11 <= $list->count()) ? 'always' : 'avoid'}};">
        <div class="text-center {{($n+1 != 1) ? 'mt-3' : ''}}">
            <h6 class="font-weight-bold">PROVINCE OF CAVITE</h6>
            <h6>Cavite De La Salle Medical Health Science Institute COVID19 Diagnostic Center</h6>
            <h6>Dasmariñas City, Cavite</h6>
        </div>
        <table class="table table-bordered mb-2">
            <thead>
                <tr style="background-color: #ffc000;">
                    <th class="text-center" colspan="8">LINELIST OF SPECIMENS REFERRED FOR COVID-19 TESTING</th>
                </tr>
                <tr>
                    <th colspan="2">Disease Reporting Unit (Hospital/Agency)</th>
                    <th colspan="2" class="text-center font-weight-normal">CITY HEALTH OFFICE - GENERAL TRIAS</th>
                    <th colspan="2">Date of Specimen Shipment (mm-dd-yyy)</th>
                    <th colspan="2" class="text-center font-weight-normal">{{date('m/d/Y', strtotime($details->laSalleDateAndTimeShipment))}}</th>
                </tr>
                <tr>
                    <th colspan="2">Referring Physician</th>
                    <th colspan="2" class="text-center font-weight-normal">{{$details->laSallePhysician}}</th>
                    <th colspan="2">Time of Specimen Shipment</th>
                    <th colspan="2" class="text-center font-weight-normal">{{date('h:i', strtotime($details->laSalleDateAndTimeShipment))}} ( {{(date('A', strtotime($details->laSalleDateAndTimeShipment)) == 'AM') ? 'X' : ''}} ) AM   ( {{(date('A', strtotime($details->laSalleDateAndTimeShipment)) == 'PM') ? 'X' : ''}} ) PM</th>
                </tr>
                <tr>
                    <th colspan="2">Contact Person</th>
                    <th colspan="2" class="text-center font-weight-normal">{{$details->contactPerson}}</th>
                    <th colspan="2">Official E-mail Address</th>
                    <th colspan="2" class="text-center font-weight-bold"><a href="#">{{$details->email}}</a></th>
                </tr>
                <tr>
                    <th colspan="2">Telephone Number</th>
                    <th colspan="2" class="text-center font-weight-normal">{{$details->contactTelephone}}</th>
                    <th colspan="2">Mobile Number</th>
                    <th colspan="2" class="text-center font-weight-normal">{{$details->contactMobile}}</th>
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
                        <td scope="row">{{($n+1)}} {{($list[$n]->res_released == 1) ? 'ok '.$list[$n]->ricon() : ''}}</td>
                        <td>{{$list[$n]->records->lname.", ".$list[$n]->records->fname." "}} {{(!is_null($list[$n]->records->mname)) ? $list[$n]->records->mname : ''}}</td>
                        <td>{{date('m/d/Y', strtotime($list[$n]->records->bdate))}}</td>
                        <td>{{(date_diff(date_create($list[$n]->records->bdate), date_create('now'))->y >= 1) ? date_diff(date_create($list[$n]->records->bdate), date_create('now'))->y : date_diff(date_create($list[$n]->records->bdate), date_create('now'))->m}} ({{(date_diff(date_create($list[$n]->records->bdate), date_create('now'))->y >= 1) ? 'X' : ' '}}) Y.O ({{(date_diff(date_create($list[$n]->records->bdate), date_create('now'))->y >= 1) ? ' ' : 'X'}}) mos.</td>
                        <td>({{($list[$n]->records->gender == "MALE") ? 'X' : ' '}}) Male ({{($list[$n]->records->gender == "FEMALE") ? 'X' : ' '}}) Female</td>
                        <td>{{date('m/d/Y', strtotime($list[$n]->dateAndTimeCollected))}}</td>
                        <td>{{date('h:i', strtotime($list[$n]->dateAndTimeCollected))}} ({{(date('A', strtotime($list[$n]->dateAndTimeCollected)) == 'AM') ? 'X' : ' '}}) AM ({{(date('A', strtotime($list[$n]->dateAndTimeCollected)) == 'PM') ? 'X' : ' '}}) PM</td>
                        <td>({{($list[$n]->remarks == '1ST') ? 'X' : ' '}}) 1st ({{($list[$n]->remarks == '2ND') ? 'X' : ' '}}) 2nd ({{($list[$n]->remarks == '3RD') ? 'X' : ' '}}) 3rd</td>
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
        <p class="my-0">NAME AND SIGNATURE: <u>{{$details->laSallePreparedBy}}</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE: <u>{{date('m/d/Y', strtotime($details->laSallePreparedByDate))}}</u> TIME: <u>{{date('h:i', strtotime($details->laSallePreparedByDate))}}</u> ( {{(date('A', strtotime($details->laSallePreparedByDate)) == 'AM') ? 'X' : ''}} ) AM ( {{(date('A', strtotime($details->laSallePreparedByDate)) == 'PM') ? 'X' : ''}} ) PM</p>
    </div>
    @endwhile
@endsection