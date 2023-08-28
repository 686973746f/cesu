@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-right mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkstock">Check Item Stock</button>
            <a href="{{route('pharmacy_itemlist')}}" class="btn btn-primary">Product List</a>
            <a href="{{route('pharmacy_viewreport')}}" class="btn btn-primary">Report</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Pharmacy Inventory System</b></div>
                    <div><b>Branch:</b> {{auth()->user()->pharmacybranch->name}}</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('pharmacy_modify_view')}}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search via QR / SKU Code" name="code" id="code" autocomplete="off" required autofocus>
                        <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection