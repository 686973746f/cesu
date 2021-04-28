@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="{{route('records.index')}}" class="btn btn-primary btn-lg btn-block">Patient Information</a>
                    <a href="{{route('forms.index')}}" class="btn btn-primary btn-lg btn-block">View/Create CIF</a>
                    <a href="{{route('linelist.index')}}" class="btn btn-primary btn-lg btn-block">Line List</a>
                    
                    @if(auth()->user()->isAdmin == 1)
                        <hr>
                        <a href="" class="btn btn-primary btn-lg btn-block">Admin Panel</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
