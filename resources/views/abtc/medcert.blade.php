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
                    <div>ABTC Online Medical Certificate</div>
                    <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
                </div>
            </div>
            <div class="card-body">
                <div id="divToPrint">
                    <div class="text-center">
                        <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                        <h2 class="my-5"><b>MEDICAL CERTIFICATE</b></h2>
                    </div>
                    <div style="font-size: 22px;margin-top: 100px;">
                        <p class="mb-5">To whom it may concern,</p>
                        <p></p>
                        @if($b->outcome == 'C')
                        <p class="mb-5">This is to certify that <u><b>{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->getName()}}</b></u>, resident of <u>{{$b->patient->getAddressMini()}}</u> has <b class="text-success">completed the required dosage of the anti-rabies vaccine</b> at the City Health Office, City of General Trias, Cavite. The vaccine was administered on <u>{{date('F d, Y', strtotime($b->d0_date))}}</u> and {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} has not shown any adverse reactions since then.</p>
                        <p class="mb-5">This certificate is being issued to confirm that {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} has completed the required doses of the anti-rabies vaccine according to the recommended schedule.</p>
                        <p class="mb-5">This certificate was issued on {{date('F d, Y')}} for whatever purpose it may serve.</p>
                        @else
                        <p class="mb-5">This is to inform you that <u><b>{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->getName()}}</b></u>, resident of <u>{{$b->patient->getAddressMini()}}</u> has <b class="text-danger">not yet completed the required dosage of the anti-rabies vaccine</b> at the City Health Office, City of General Trias, Cavite. As of {{date('F d, Y')}}, {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} has received {{$b->getNumOfCompletedDose()}} out of the required {{($b->is_booster == 0) ? '3 Doses' : '2 Doses'}}.</p>
                        <p class="mb-5">Please be advised that it is important for {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} to complete the entire vaccine schedule to ensure protection against rabies. We recommend scheduling the next dose as soon as possible.</p>
                        <p>If you need any further information or assistance, please don't hesitate to contact us.</p>
                        @endif
                        <p>Thank you very much.</p>
                        <p style="margin-bottom: 100px;">Sincerely,</p>
                        <p><b>JONATHAN P. LUSECO, MD</b></p>
                        <p style="margin-top: -20px;">City Health Officer II</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection