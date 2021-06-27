@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Schedule Details</div>
            <div class="card-body text-center">
                <p>Good Day, <strong>{{$data->getName()}}</strong></p>
                <hr>
                @if($data->status == 'pending')
                    <p>The request you submitted on <strong>{{date('m/d/Y h:i A', strtotime($data->created_at))}}</strong> is still <strong>under pending</strong>.</p>
                    <p>It will be checked by CESU Encoders/Staffs as soon as possible.</p>
                @elseif($data->status == 'rejected')
                    Rejected
                @else
                    Approved
                @endif
                <hr>
                <p>If you have inquiries, you may reach us with the contact details below:</p>
            </div>
        </div>
    </div>
@endsection