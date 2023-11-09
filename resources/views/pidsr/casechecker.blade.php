@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>PIDSR Cases Viewer</b></div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Select Case and Year</span>
                    </div>
                    <select class="custom-select" name="case" id="case" required>
                        <option disabled {{(is_null(request()->input('case'))) ? 'selected' : ''}}>Select Type...</option>
                        <option value="ABD" {{(request()->input('case') == 'ABD') ? 'selected' : ''}}>ABD</option>
                        <option value="AEFI" {{(request()->input('case') == 'AEFI') ? 'selected' : ''}}>AEFI</option>
                        <option value="AES" {{(request()->input('case') == 'AES') ? 'selected' : ''}}>AES</option>
                        <option value="AFP" {{(request()->input('case') == 'AFP') ? 'selected' : ''}}>AFP</option>
                        <option value="AHF" {{(request()->input('case') == 'AHF') ? 'selected' : ''}}>AHF</option>
                        <option value="AMES" {{(request()->input('case') == 'AMES') ? 'selected' : ''}}>AMES</option>
                        <option value="ANTHRAX" {{(request()->input('case') == 'ANTHRAX') ? 'selected' : ''}}>ANTHRAX</option>
                        <option value="CHIKV" {{(request()->input('case') == 'CHIKV') ? 'selected' : ''}}>CHIKV</option>
                        <option value="CHOLERA" {{(request()->input('case') == 'CHOLERA') ? 'selected' : ''}}>CHOLERA</option>
                        <option value="DENGUE" {{(request()->input('case') == 'DENGUE') ? 'selected' : ''}}>DENGUE</option>
                        <option value="DIPH" {{(request()->input('case') == 'DIPH') ? 'selected' : ''}}>DIPH</option>
                        <option value="HEPATITIS" {{(request()->input('case') == 'HEPATITIS') ? 'selected' : ''}}>HEPATITIS</option>
                        <option value="HFMD" {{(request()->input('case') == 'HFMD') ? 'selected' : ''}}>HFMD</option>
                        <option value="INFLUENZA" {{(request()->input('case') == 'INFLUENZA') ? 'selected' : ''}}>INFLUENZA</option>
                        <option value="LEPTOSPIROSIS" {{(request()->input('case') == 'LEPTOSPIROSIS') ? 'selected' : ''}}>LEPTOSPIROSIS</option>
                        <option value="MALARIA" {{(request()->input('case') == 'MALARIA') ? 'selected' : ''}}>MALARIA</option>
                        <option value="MEASLES" {{(request()->input('case') == 'MEASLES') ? 'selected' : ''}}>MEASLES</option>
                        <option value="MENINGITIS" {{(request()->input('case') == 'MENINGITIS') ? 'selected' : ''}}>MENINGITIS</option>
                        <option value="MENINGO" {{(request()->input('case') == 'MENINGO') ? 'selected' : ''}}>MENINGO</option>
                        <option value="NNT" {{(request()->input('case') == 'NNT') ? 'selected' : ''}}>NNT</option>
                        <option value="NT" {{(request()->input('case') == 'NT') ? 'selected' : ''}}>NT</option>
                        <option value="PERT" {{(request()->input('case') == 'PERT') ? 'selected' : ''}}>PERT</option>
                        <option value="PSP" {{(request()->input('case') == 'PSP') ? 'selected' : ''}}>PSP</option>
                        <option value="RABIES" {{(request()->input('case') == 'RABIES') ? 'selected' : ''}}>RABIES</option>
                        <option value="ROTAVIRUS" {{(request()->input('case') == 'ROTAVIRUS') ? 'selected' : ''}}>ROTAVIRUS</option>
                        <option value="TYPHOID" {{(request()->input('case') == 'TYPHOID') ? 'selected' : ''}}>TYPHOID</option>
                    </select>
                    <select class="custom-select" name="year" id="year" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year...</option>
                        @foreach(range(date('Y'), 2015) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            @if(isset($list))
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="list_table">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            @foreach($columns as $c)
                            <th>{{ucfirst($c)}}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $l)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            @foreach($columns as $c)
                            <td>{{mb_strtoupper($l->$c)}}</td>
                            @endforeach
                            <td><a href="{{route('pidsr_casechecker_action', ['d' => request()->input('case'), 'action' => 'DEL', 'epi_id' => $l->EPIID])}}" class="btn btn-warning" onclick="return confirm('Proceed to disable? The record will not be listed anymore after processing.')">Disable</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    $('#list_table').dataTable({
        fixedHeader: true,
        dom: 'QBfritp',
        buttons: [
            'excel', 'copy',
        ],
    });
</script>
@endsection