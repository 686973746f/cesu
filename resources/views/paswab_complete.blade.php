@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-success">
            <div class="card-header">Completing the Registration</div>
            <div class="card-body text-center">
                <p>Thank you for submitting your information.</p>
                <p>Your request will be processed by CESU CHO General Trias in 1-3 Days.</p>
                <hr>
                <p>Your Schedule Code is:</p>
                <strong class="my-3"><h3>{{session('majik')}}</h3></strong>
                <p>Please save the code as you can use it to check when is the schedule of your swab.</p>
                <p>You will be also informed in Barangay regarding your schedule.</p>
                <hr>
                <p>If you have any concerns, you may contact us at:</p>
                <p>09361234567</p>
                <p>cesu.gentrias@gmail.com</p>
                <hr>
                <p>Thank you. Keep Safe.</p>
            </div>
        </div>
    </div>
@endsection