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
                        @php
                        if(auth()->user()->itr_facility_id == 10886) {
                            $facility_name = 'City Health Office, Brgy. Pinagtipunan, City of General Trias, Cavite';
                        }
                        else if(auth()->user()->itr_facility_id == 11730) {
                            $facility_name = 'Manggahan Main Health Center, City of General Trias, Cavite';
                        }
                        else if(auth()->user()->itr_facility_id == 39708) {
                            $facility_name = 'General Trias Super Health Center, Brgy. San Francisco, City of General Trias, Cavite';
                        }
                        else {
                            $facility_name = 'City Health Office, Brgy. Pinagtipunan, City of General Trias, Cavite';
                        }
                        @endphp
                        @if($b->outcome == 'C')
                        <p class="mb-5">This is to certify that <u><b>{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->getName()}}</b></u>, resident of <u>{{$b->patient->getAddressMini()}}</u> has <b class="text-success">completed the required dosage of the anti-rabies vaccine</b> at the {{$facility_name}}. The vaccine was administered on <u>{{date('F d, Y', strtotime($b->d0_date))}}</u> for Day 0, <u>{{date('F d, Y', strtotime($b->d3_date))}}</u> for Day 3 @if($b->is_booster != 1), and <u>{{date('F d, Y', strtotime($b->d7_date))}}</u> for Day 7 @else . @endif And {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} has not shown any adverse reactions since then.</p>
                        <p class="mb-5">{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} was exposed on <b>{{date('F d, Y', strtotime($b->bite_date))}}</b> by a <b>{{$b->getSource()}}</b> and was assessed by a medical professional to be under <b>Category {{$b->category_level}}</b> exposure to rabies.</p>
                        <p class="mb-5">This certificate was issued on {{date('F d, Y')}} for whatever purpose it may serve.</p>
                        @else
                        <p class="mb-5">This is to inform you that <u><b>{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->getName()}}</b></u>, resident of <u>{{$b->patient->getAddressMini()}}</u> has <b class="text-danger">not yet completed the required dosage of the anti-rabies vaccine</b> at the City Health Office, City of General Trias, Cavite. As of {{date('F d, Y')}}, {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} has received {{$b->getNumOfCompletedDose()}} out of the required {{($b->is_booster == 0) ? '3 Doses' : '2 Doses'}}.</p>
                        <p class="mb-5">{{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} was exposed on <b>{{date('F d, Y', strtotime($b->bite_date))}}</b> by a <b>{{$b->getSource()}}</b> and was assessed by a medical professional to be under <b>Category {{$b->category_level}}</b> exposure to rabies.</p>
                        <p class="mb-5">Please be advised that it is important for {{($b->patient->gender == 'MALE') ? 'MR. ' : 'MS. ' }}{{$b->patient->lname}} to complete the entire vaccine schedule to ensure protection against rabies. We recommend scheduling the next dose as soon as possible.</p>
                        <p>If you need any further information or assistance, please don't hesitate to contact us.</p>
                        @endif
                        <p>Thank you very much.</p>
                        <p style="margin-bottom: 50px;">Sincerely,</p>
                        <p><b>{{request()->input('vaccinator')}}</b></p>
                        <p style="margin-top: -20px;margin-bottom: 50px;">Vaccinator</p>

                        <p style="margin-bottom: 50px;">Noted by:</p>

                        @if(request()->input('doctor') == 'DOC_ATHAN')
                        <p><b>JONATHAN P. LUSECO, MD</b></p>
                        <p style="margin-top: -20px;">City Health Officer II</p>
                        @elseif(request()->input('doctor') == 'DOC_ABE')
                        <p><b>ABE D. ESCARIO, MD</b></p>
                        <p style="margin-top: -20px;">City Health Officer I</p>
                        @elseif(request()->input('doctor') == 'DOC_YVES')
                        <p><b>YVES M. TALOSIG, MD</b></p>
                        <p style="margin-top: -20px;">Medical Officer III</p>
                        @elseif(request()->input('doctor') == 'DRA_CHERRY')
                        <p><b>CHERRY L. ASPURIA, MD</b></p>
                        <p style="margin-top: -20px;">Medical Officer IV</p>
                        @elseif(request()->input('doctor') == 'DOC_ED')
                        <p><b>EDGARDO R. FIGUEROA, MD</b></p>
                        <p style="margin-top: -20px;">Medical Officer III</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection