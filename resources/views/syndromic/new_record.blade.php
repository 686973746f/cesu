@extends('layouts.app')

@section('content')
<div class="container">
    <form action="">
        @csrf
        <div class="card">
            <div class="card-header"><b>New ITR - Step 3/3</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="consulation_date">Date and Time of Consultation</label>
                            <input type="datetime-local" class="form-control" name="consulation_date" id="consulation_date" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">

                    </div>
                </div>
                
            </div>
        </div>
    </form>
</div>
@endsection