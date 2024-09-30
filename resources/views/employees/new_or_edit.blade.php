@extends('layouts.app')

@section('content')
    @if($mode == 'EDIT')
    <form action="{{route('employees_update', $d->id)}}" method="POST">
    @else
    <form action="{{route('employees_store')}}" method="POST">
    @endif

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Employee</b></div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    </form>
@endsection