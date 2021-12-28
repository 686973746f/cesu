@extends('layouts.app')

@section('content')
<div class="container" style="font-family: Arial, Helvetica, sans-serif">
    <div class="card">
        <div class="card-header">Record Confidential</div>
        <div class="card-body text-center">
            <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
            <p class="h3">Warning!</p>
            <p>Patient record of <u>{{$record->getName()}} (#{{$record->id}})</u> was marked as <strong>Confidential</strong></p>
            <p>Only authorized users are allowed to view and edit the record.</p>
            <hr>
            <p>If you think this was a mistake, please contact CESU Staff.</p>
        </div>
        <div class="card-footer text-center">
            <a href="{{url()->previous()}}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Go Back</a>
        </div>
    </div>
</div>
@endsection