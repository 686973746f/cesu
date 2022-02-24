@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Online Medical Certificate</div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn">Print</button></div>
            </div>
        </div>
        <div class="card-body" id="divToPrint">
            <p>Test</p>
        </div>
    </div>
</div>

<script>
    $('#PrintBtn').click(function (e) { 
        e.preventDefault();
        $('#divToPrint').printThis();
    });
</script>
@endsection