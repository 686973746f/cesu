@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome: {{strtoupper(auth()->user()->name)}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{route('records.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user mr-2" aria-hidden="true"></i>Patient Information</a>
                    <a href="{{route('forms.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-file mr-2" aria-hidden="true"></i>View/Create CIF</a>
                    <a href="{{route('linelist.index')}}" class="btn btn-primary btn-lg btn-block">Line List <i class="font-weight-bold text-warning">(Under development)</i></a>
                    <hr>
                    <a href="" class="btn btn-primary btn-lg btn-block"><i class="fas fa-chart-bar mr-2"></i>Reports <i class="font-weight-bold text-warning">(Under development)</i></a>
                    @if(auth()->user()->isAdmin == 1)
                        <hr>
                        <a href="{{route('adminpanel.index')}}" class="btn btn-primary btn-lg btn-block">Admin Panel</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
