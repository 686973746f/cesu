@extends('layouts.app_pdf')

@section('content')
    <div class="container-fluid">
        <div class="text-center">
            <h6 class="font-weight-bold">PROVINCE OF CAVITE</h6>
            <h6>Cavite De La Salle Medical Health Science Institute COVID19 Diagnostic Center</h6>
            <h6>Dasmariñas City, Cavite</h6>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color: #ffc000;">
                    <th class="text-center" colspan="4">LINELIST OF SPECIMENS REFERRED FOR COVID-19 TESTING</th>
                </tr>
                <tr>
                    <th>Disease Reporting Unit (Hospital/Agency)</th>
                    <th class="text-center">CITY HEALTH OFFICE - GENERAL TRIAS</th>
                    <th>Date of Specimen Shipment (mm-dd-yyy)</th>
                    <th class="text-center">{{date('m/d/Y', strtotime($details->laSalleDateAndTimeShipment))}}</th>
                </tr>
                <tr>
                    <th>Referring Physician</th>
                    <th class="text-center">{{$details->laSallePhysician}}</th>
                    <th>Time of Specimen Shipment</th>
                    <th class="text-center">{{date('h:i', strtotime($details->laSalleDateAndTimeShipment))}} ( {{(date('A') == 'AM') ? 'X' : ''}} ) AM   ( {{(date('A') == 'PM') ? 'X' : ''}} ) PM</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td scope="row"></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection