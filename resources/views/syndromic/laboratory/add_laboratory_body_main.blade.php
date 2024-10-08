@extends('layouts.app')

@section('content')
    <form action="{{route('syndromic_store_labresult', [$d->id, $case_code])}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>Add Laboratory Data - {{$case_code}}</b></div>
                <div class="card-body">
                    @if($case_code == 'Dengue')
                    
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </form>
@endsection