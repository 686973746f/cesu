@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Test</div>
            <div class="card-body">
                @foreach($get_list as $d)
                <a href="{{route('edcs_barangay_quicklogin', ['brgy' => $d->brgyName, 'qlcode' => $d->edcs_quicklogin_code])}}">{{$d->brgyName}}</a>
                @endforeach
            </div>
        </div>
    </div>
@endsection