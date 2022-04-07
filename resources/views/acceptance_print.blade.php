@extends('layouts.app')

@section('content')
<style>
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 70%;
    }

    @media print {
        #PrintBtn, #titleBody {
            display: none;
        }

        @page {
            margin: 0;
        }

        body {
            background-color: white;
        }

        body * {
            visibility: hidden;
        }

        #divToPrint, #divToPrint * {
            visibility: visible;
        }

        #divToPrint {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><strong>Online Acceptance Letter</strong></div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
            </div>
        </div>
        <div class="card-body" style="font-family: Arial, Helvetica, sans-serif">
            <div id="divToPrint">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                </div>
                <p class="text-center my-5" style="font-size: 24px;"><strong><u>LETTER OF ACCEPTANCE</u></strong></p>
                <p style="margin-left:5em;margin-right:5em;font-size:18px;text-align:justify;" class="mb-5">TO WHOM IT MAY CONCERN:</p>
                <p style="margin-left:5em;margin-right:5em;font-size:18px;text-align:justify;">&emsp;&emsp;This is to certify <strong><u>{{$gcheck}}. {{$data->getName()}}</u></strong> is a resident of <strong><u>{{$data->getAddress()}}.</u></strong></p>
                <p style="margin-left:5em;margin-right:5em;font-size:18px;text-align:justify;">&emsp;&emsp;The City Government of General Trias, Cavite as their DOMICILE will be travelling from <strong><u>{{$data->travelto}}</u></strong> due to PANDEMIC Corona Virus (COVID-19). Provided that {{strtolower($gcheck1)}} shall comply with the following:</p>
                <p style="margin-left:10em;margin-right:5em;font-size:18px;text-align:justify;">1. Strict compliance to <strong>Home Quarantine/Isolation Facility protocol for 21 days (if immunocompromised) or 14 days (if partial/unvaccinated) or 7 days (if fully vaccinated)</strong> which means that {{strtolower($gcheck1)}} <strong>shall not be allowed to go out of this house.</strong></p>
                <p style="margin-left:10em;margin-right:5em;font-size:18px;text-align:justify;">2. {{ucwords($gcheck1)}} will be <strong>checked daily</strong> for any symptoms of disease by the BHERT or any representative of the City Health or Barangay officials.</p>
                <p style="margin-left:10em;margin-right:5em;font-size:18px;text-align:justify;">3. Compliance with the rules and regulations promulgated by the City Government.</p>
                <p style="margin-left:5em;margin-right:5em;font-size:18px;text-align:justify;">This certificate is issued for record and reference purposes.</p>
                <p style="margin-left:5em;margin-right:5em;font-size:18px;text-align:justify;">Issued this {{$curr_date}} at Office of the City Health Officer, City of General Trias, Cavite.</p>
                <img src="{{asset('assets/images/signatureonly_docathan.png')}}" style="width: 12rem;margin-left:7em;margin-bottom:-50px;">
                <p style="margin-left:5em;margin-bottom: 0px;font-size:18px;"><strong><u>JONATHAN P. LUSECO, MD</u></strong></p>
                <p style="margin-left:5em;margin-bottom: 0px;font-size:18px;">City Health Officer II</p>
                <p style="margin-left:5em;margin-bottom: 0px;font-size:18px;">City of General Trias</p>
                <div class="text-center mt-5">
                    <p style="margin-bottom: 1px;"><strong>Office of the City Health Officer</strong></p>
                    <p style="margin-bottom: 1px;">Pria Rd, Brgy. Pinagtipunan, General Trias, City, 4107 Cavite</p>
                    <p style="margin-bottom: 1px;">Website: <a href="#">https://generaltrias.gov.ph/cho/</a></p>
                    <p style="margin-bottom: 1px;">Contact No: (046) 509 - 5289</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection