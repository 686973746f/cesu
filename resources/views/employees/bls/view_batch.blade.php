@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>{{$d->batch_name}}</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParticipant">Add Participant</button>
                </div>
            </div>
        </div>
        <div class="card-body">

        </div>
    </div>
</div>

<form action="{{route('bls_storemember', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="addParticipant" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Participant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Body
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection