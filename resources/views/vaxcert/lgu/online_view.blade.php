@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                    <h4 class="mb-5"><b>COVID-19 Vaccine Certificate Verification</b></h4>
                    <h6>Beware of fake verification sites. The legitimate site should have this domain name <span class="text-success"><b>https://cesugentri.com/vaxcert_lgu/verify/</b></span></h6>
                </div>
                <hr>
                @if($d)
                
                <div class="text-center">
                    <h1 class="text-success"><i class="fas fa-check-circle fa-2x"></i></h1>
                    <h3 class="text-success"><b>This LGU VaxCert is verified</b></h3>
                    
                    <h4 class="mt-5"><b>{{$d->getName()}}</b></h4>
                    <h5>Birthdate: {{Carbon\Carbon::parse($d->bdate)->format('M. d, Y')}}</h5>

                    <h5 class="mt-5">Doses: {{$d->numberOfDoses()}}</h5>
                    @if($d->dose1_vaccine_manufacturer_name == 'J&J')
                    <h5>1st and 2nd Dose (J&J Vaccine): {{($d->dose1_city == 'Y') ? 'Yes' : 'Yes (Outside General Trias)'}}</h5>
                    @else
                    <h5>1st Dose: {{($d->dose1_city == 'Y') ? 'Yes' : 'Yes (Outside General Trias)'}}</h5>
                    <h5>2nd Dose: {{($d->process_dose2 == 'Y') ? ($d->dose2_city == 'Y') ? 'Yes' : 'Yes (Outside General Trias)' : 'N/A'}}</h5>
                    @endif
                    <h5>3rd Dose: {{($d->process_dose3 == 'Y') ? ($d->dose3_city == 'Y') ? 'Yes' : 'Yes (Outside General Trias)' : 'N/A'}}</h5>
                    <h5>4th Dose: {{($d->process_dose4 == 'Y') ? ($d->dose4_city == 'Y') ? 'Yes' : 'Yes (Outside General Trias)' : 'N/A'}}</h5>
                </div>
                @else
                <div class="text-center">
                    <h1 class="text-danger"><i class="fas fa-times-circle fa-2x"></i></h1>
                    <h3 class="text-danger"><b>INVALID QR CODE</b></h3>
                    <p>Sorry, your QR Code is invalid.</p>
                </div>
                @endif
            </div>
        </div>
        <div class="mt-3 text-center">
            <code class=" text-muted">CHO General Trias Online Verification Tool. Developed and Maintained by Christian James Historillo.</code>
        </div>
    </div>
@endsection