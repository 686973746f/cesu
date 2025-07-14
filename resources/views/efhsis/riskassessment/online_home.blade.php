@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('onlinenc_register')}}" method="GET">
                    <div class="card">
                        <div class="card-header">
                            <div><b>Online CVD/NCD Risk Assessment Form</b></div>
                            @if(!is_null($f))
                            <div>BHS: {{$f->name}}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                                {{session('msg')}}
                            </div>
                            @endif
                            @include('efhsis.riskassessment.modal_content')
                        </div>
                        <div class="card-footer">
                            <div class="alert alert-info" role="alert">
                                Sa pagpapatuloy, sumasang-ayon ka sa <b>Republic Act 11332</b> at sa <b>Data Privacy Act of 2012</b>, at gagamitin ng City Health Office ng General Trias ang iyong impormasyon para sa Electronic Field Health Service Information System (eFHSIS) nang may mahigpit na pagiging kumpidensyal.
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Next</button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small>Developed and Maintained by CJH for General Trias City Health Office.</small>
                </div>
            </div>
        </div>
    </div>
@endsection