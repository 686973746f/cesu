@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>{{$s->name}}</b></div>
                <div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
                      Launch
                    </button>
                    
                    <!-- Modal -->
                    
                </div>
            </div>
        </div>
        <div class="card-body">

        </div>
    </div>
</div>
@endsection