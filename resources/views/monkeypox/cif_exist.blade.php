@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Exists</div>
        <div class="card-body">
            <a href="{{route('mp.editcif', ['mk' => $d->id])}}">Edit</a>
        </div>
    </div>
</div>
@endsection