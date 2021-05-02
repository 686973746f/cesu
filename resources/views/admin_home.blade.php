@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Admin Panel</div>
        <div class="card-body">
            <a class="btn btn-primary btn-block mb-3" href="{{route('adminpanel.account.index')}}" role="button">Admin Accounts</a>
            <a class="btn btn-primary btn-block mb-3" href="{{route('adminpanel.brgy.index')}}" role="button">Barangay Accounts</a>
        </div>
    </div>
</div>
@endsection