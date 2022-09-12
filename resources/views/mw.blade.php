@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Morbidity Week Updater</div>
        <div class="card-body">
            <form action="{{route('mw.process')}}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <select class="custom-select" name="year" id="year" required>
                        <option value="" {{(request()->input('year')) ? '' : 'selected'}}>Select Year...</option>
                        @foreach(range(date('Y'), 2019) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection