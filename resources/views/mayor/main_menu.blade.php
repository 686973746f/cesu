@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header"><b>Main Menu</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}}" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <a href="{{route('mayor_pharmacy_main_menu')}}" class="btn btn-primary btn-block btn-lg">Pharmacy Inventory System</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection