@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Admin Panel</div>
        <div class="card-body">
            <a class="btn btn-primary btn-block mb-3" href="{{route('forms.import')}}" role="button">Import CIF</a>
            <a class="btn btn-primary btn-block mb-3" href="{{route('adminpanel.account.index')}}" role="button">Admin Accounts</a>
            <a class="btn btn-primary btn-block mb-3" href="{{route('adminpanel.brgy.index')}}" role="button">Barangay Accounts</a>
            <a class="btn btn-primary btn-block mb-3" href="{{route('interviewers.index')}}" role="button">Interviewers</a>
            <a class="btn btn-primary btn-block mb-3" href="{{route('companies.index')}}">Companies</a>
            <a class="btn btn-primary btn-block" href="{{route('paswablinks.index')}}">Pa-swab Links</a>
        </div>
    </div>
</div>
@endsection