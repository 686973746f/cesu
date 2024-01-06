@extends('layouts.app')

@section('content')
<form action="{{route('settings_general_update')}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><b>General Settings</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                  <label for="default_holiday_dates">Default Holiday Dates</label>
                  <input type="text" class="form-control" name="default_holiday_dates" id="default_holiday_dates" value="{{$d->default_holiday_dates}}">
                  <small>Holidays that are default every year (ex. New Year, Christmas). Can be written as MM-DD and can be separated with commas (,) for multiple inputs.</small>
                </div>
                <div class="form-group">
                    <label for="custom_holiday_dates">Custom Holiday per Year {{date('(Y)')}}</label>
                    <input type="text" class="form-control" name="custom_holiday_dates" id="custom_holiday_dates" value="{{$d->custom_holiday_dates}}">
                    <small>Holidays that are changing per year. Can be written as MM-DD and can be separated with commas (,) for multiple inputs.</small>
                  </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>
    
@endsection