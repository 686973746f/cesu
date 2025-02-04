@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('onlinenc_register')}}" method="GET">
                    <div class="card">
                        <div class="card-header"><b>Online CVD/NCD Risk Assessment Form</b></div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                                {{session('msg')}}
                            </div>
                            @endif
                            @include('efhsis.riskassessment.modal_content')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection