@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    Line List
                </div>
                <div>
                    <a href="{{route('linelist.createoni')}}" class="btn btn-success">Create ONI</a>
                </div>
            </div>
        </div>
        <div class="card-body">

        </div>
    </div>
</div>
@endsection
