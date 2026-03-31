@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-6 text-center">
            <img src="{{asset('assets/images/gentri_icon_large.png')}}" class="mb-3" style="width: 12rem;">
            <img src="{{asset('assets/images/cho_icon_large.png')}}" class="mb-3" style="width: 12rem;">
            <img src="{{asset('assets/images/cesu_icon.png')}}" class="mb-3" style="width: 12rem;">
        </div>
        <div class="col-md-6 mt-5">
            <div class="text-center">
                <h1 class="text-success"><b>Project IDRISH</b></h1>
                <h3>CESU General Trias Integrated Disease Reporting and Information System for Health</h3>
            </div>
        </div>
    </div>
</div>
@endsection