@extends('layouts.app')

@section('content')
<style>
    @if($p->facility_id == 10886 || $p->facility_id == 39708 || $p->facility_id == 11730)
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 50%;
    }
    @endif

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
                    <div><b>Laboratory Result</b></div>
                    <div><button type="button" class="btn btn-success" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print <i>(CTRL + P)</i></button></div>
                </div>
            </div>
            <div class="card-body" id="divToPrint">
                @if($p->ifChoRecord())

                <div class="d-flex justify-content-center">
                    <div class="">
                        <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" style="margin-top: 0px; width: 50rem;">
                    </div>
                    <div class="text-center">
                        <div class="ml-3">{!! QrCode::size(150)->generate(route('laboratory_online_verify', $d->hash_qr)) !!}</div>
                        <small class="ml-3">Scan QR to Verify</small>
                    </div>
                </div>
                <div class="text-center">
                    @if($p->facility_id == 10886)
                    <h5>Hospital Rd., Brgy. Pinagtipunan, General Trias, Cavite</h5>
                    <h5>Tel No. (046) 509-5289</h5>
                    @elseif($p->facility_id == 11730)
                    <h5><b>General Trias City Health Center - Brgy. Manggahan</b></h5>
                    <h5>Opal St., Sunshine Village, Brgy. Manggahan, General Trias, Cavite</h5>
                    @elseif($p->facility_id == 39708)
                    <h5><b>General Trias Super Health Center - Brgy. San Francisco</b></h5>
                    <h5>Arnaldo Highway, Sitio Elang, Brgy. San Francisco, General Trias, Cavite</h5>
                    @endif
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div><h4>Name: <u>{{$p->syndromic_patient->getName()}}</u></h4></div>
                    <div><h4>Date: <u>{{date('M. d, Y', strtotime($p->consultation_date))}}</u></h4></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div><h4>Age/Sex: <u>{{$p->syndromic_patient->getAge()}} / {{$p->syndromic_patient->gender}}</u></h4></div>
                    <div><h4>Case No.: <u>#{{$p->id}}</u></h4></div>
                </div>
                @if($d->case_code == 'Dengue')
                <h4 class="text-center"><b><u>SEROLOGY</u></b></h4>
                <h4 class="text-center mb-5"><b>Dengue NS1 / IgG / IgM</b></h4>
                
                <table class="table table-bordered text-center">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>TEST</th>
                            <th>RESULT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($l as $m)
                        <tr>
                            <td>{{$m->test_type}}</td>
                            <td class="text-{{$m->resultColor()}}">
                                <b>{{$m->result}}</b>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
                <h6 class="text-center mb-5">*END OF REPORT*</h6>

                <div class="text-center mb-5">
                    @php
                    $mt = explode(';', $d->collected_by)
                    @endphp

                    <h5><b><u>{{$mt[0]}}</u></b></h5>
                    <h5>{{$mt[1]}}</h5>
                    <h5>LIC NO.: {{$mt[2]}}</h5>
                </div>
                @else

                @endif

                <h4 class="bg-warning text-center">"THIS RESULT IS VALID FOR CITY OF GENERAL TRIAS-CITY HEALTH OFFICE ONLY."</h4>
                <!-- <h4 class="text-center mt-3"><b><span class="text-primary">Let's Join Forces For a Healthier GenTri</span></b></h4> -->

                @elseif($p->ifMedicareRecord())
                <div class="d-flex justify-content-between text-center">
                    <div>
                        <img src="{{asset('assets/images/medicare_logo.png')}}" alt="" style="width: 8rem;">
                    </div>
                    <div>
                        <h4>REPUBLIC OF THE PHILIPPINES</h4>
                        <h4><b>CITY OF GENERAL TRIAS MEDICARE HOSPITAL</b></h4>
                        <h6>Brgy. Pinagtipunan, General Trias City, Cavite / Tel No. (046) 509-0064</h6>
                        <h6>DOH License No.: 4A-0023-24-I-1</h6>
                    </div>
                    <div>
                        <img src="{{asset('assets/images/gentri_icon_large.png')}}" alt="" style="width: 8rem;">
                        <img src="{{asset('assets/images/ljf.png')}}" alt="" style="width: 8rem;">
                    </div>
                </div>
                <h4 class="text-center bg-info"><b>DEPARTMENT OF LABORATORY MEDICINE</b></h4>

                @if($d->case_code == 'Dengue')
                <div class="d-flex justify-content-center mb-3">
                    <div>
                        <h4 class="text-center mt-3"><b><u>SEROLOGY</u></b></h4>
                        <h4 class="text-center mb-5"><b>Dengue NS1 / IgG / IgM</b></h4>
                    </div>
                    <div class="ml-3">
                        <div>{!! QrCode::size(130)->generate(route('laboratory_online_verify', $d->hash_qr)) !!}</div>
                        <small class="ml-3">Scan QR to Verify</small>
                    </div>
                </div>
                
                
                <table class="table table-bordered text-center">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>TEST</th>
                            <th>RESULT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($l as $m)
                        <tr>
                            <td>{{$m->test_type}}</td>
                            <td class="text-{{$m->resultColor()}}">
                                <b>{{$m->result}}</b>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h6 class="text-center mb-5">*END OF REPORT*</h6>

                <div class="text-center mb-5">
                    @php
                    $mt = explode(';', $d->collected_by)
                    @endphp

                    <h5><b><u>{{$mt[0]}}</u></b></h5>
                    <h5>{{$mt[1]}}</h5>
                    <h5>LIC NO.: {{$mt[2]}}</h5>
                </div>
                @else

                @endif
                @endif
            </div>
        </div>
    </div>
@endsection