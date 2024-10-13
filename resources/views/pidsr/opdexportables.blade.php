@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_opdexportables_process')}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>OPD Cases Exportables</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" name="submit" value="Dengue" class="btn btn-primary btn-lg btn-block" {{($dengue_count == 0) ? 'disabled' : ''}}>Dengue ({{$dengue_count}})</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
@endsection