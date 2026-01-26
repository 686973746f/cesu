@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header"><b>{{ $type }}</b></div>
        <div class="card-body">
            @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}">
                    {{session('msg')}}
                </div>
            @endif

            @if($type == 'maternal_care')
                @include('efhsis.etcl.maternalcare_list')
            @elseif($type == 'child_care')
                @include('efhsis.etcl.childcare_list')
            @else
                <div class="alert alert-warning">
                    Please select a valid eTCL module from the <a href="{{route('etcl_home')}}">eTCL Home</a>.
                </div>
            @endif
        </div>
    </div>
@endsection