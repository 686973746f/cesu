@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-warning">
        <div class="card-header text-center bg-warning text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Dengue CIF already exists for <a href="{{route('records.edit', $d->records->id)}}">{{$d->records->getName()}}</a> <small>(Patient ID: #{{$d->records->id}} | Dengue CIF ID: #{{$d->id}})</small></div>
        <div class="card-body">

        </div>
        <div class="card-footer">
            <a href="{{route('dg.editcif', ['cif_id' => $d->id])}}" class="btn btn-primary btn-block">View / Edit</a>
        </div>
    </div>
</div>
@endsection