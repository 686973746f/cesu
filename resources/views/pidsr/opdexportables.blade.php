@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_opdexportables_process')}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>OPD Cases Exportables</b></div>
                <div class="card-body">
                    <button type="submit" name="submit" value="Dengue" class="btn btn-primary">Dengue ({{$dengue_count}})</button>
                </div>
            </div>
        </div>
    </form>
@endsection