@extends('layouts.app')

@section('content')
<style>
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
            <div class="card-header"><b>Your Pharmacy Card</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <button type="button" name="" id="" class="btn btn-success btn-block" onclick="window.print()">Download/Print your Card</button>
                <hr>
                <div id="divToPrint">
                    <div class="mx-3">
                        <div class="text-center">
                            <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="mb-3 img-fluid">
                            <h4 class="mb-5"><b><u>PHARMACY PATIENT CARD</u></b></h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>NAME:</b> {{$d->getName()}}</h5>
                                <h5><b>BIRTHDATE:</b> {{date('m/d/Y', strtotime($d->bdate))}}</h5>
                                <h5><b>SEX:</b> {{$d->gender}}</h5>
                                <h5><b>BARANGAY:</b> {{$d->address_brgy_text}}</h5>
                                <h5 class="mt-5"><b>REG. NO:</b> #{{$d->id}}</h5>
                                <h5><b>DATE REGISTERED:</b> {{date('m/d/Y', strtotime($d->created_at))}}</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">{!! QrCode::size(130)->generate('PATIENT_'.$d->qr) !!}</div>
                                <h5 class="text-center mt-3"><b>BRANCH:</b> <u>{{$d->pharmacybranch->name}}</u></h5>
                            </div>
                        </div>
                        <h4 class="text-center mt-3"><b><span class="text-primary">Let's Join Forces</span> <span class="text-success">For a Healthier</span> <span class="text-success">Gen</span><span class="text-primary">Tri</span></b></h4>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection