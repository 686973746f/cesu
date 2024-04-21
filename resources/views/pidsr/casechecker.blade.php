@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>EDCS-IS In-house List of Case Viewer</b></div>
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
                        <option value="SARI" {{(request()->input('case') == 'SARI') ? 'selected' : ''}}>SARI</option>
                        <option value="TYPHOID" {{(request()->input('case') == 'TYPHOID') ? 'selected' : ''}}>TYPHOID</option>
                    </select>
                    <select class="custom-select" name="year" id="year" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year...</option>
                        @foreach(range(date('Y'), 2015) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="showDisabled" id="showDisabled" value="1" {{(request()->input('showDisabled')) ? 'checked' : ''}}> Show Disabled Cases
                  </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="showNonMatchCaseDef" id="showNonMatchCaseDef" value="1" {{(request()->input('showNonMatchCaseDef')) ? 'checked' : ''}}> Show NOT Match on Case Def.
                    </label>
                </div>
            </form>
            @if(isset($list))
            <hr>
            <table class="table table-bordered table-striped table-hover" id="list_table" style="width:100%">
                <thead class="thead-light text-center">
                    <tr>
                        <!-- <th></th> -->
                        <th>#</th>
                        <th>Details</th>
                        @foreach($columns as $c)
                        <th>{{ucfirst($c)}}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $l)
                    @php
                    $setBgColor = '';

                    if($l->match_casedef == 0) {
                        $setBgColor = 'bg-warning';
                    }

                    if($l->enabled == 0) {
                        $setBgColor = 'bg-danger text-white';
                    }
                    @endphp
                    <tr class="{{$setBgColor}}">
                        <!-- <td></td> -->
                        <td class="text-center">{{$key+1}}</td>
                        <td class="text-center btn-group">
                            @php
                            if(request()->input('case') == 'SARI') {
                                $epi_id = $l->epi_id;
                            }
                            else {
                                $epi_id = $l->EPIID;
                            }
                            @endphp 
                            <a href="{{route('pidsr_viewcif', [$case_name, $epi_id])}}" class="btn btn-primary"><i class="fa fa-file" aria-hidden="true"></i></a>
                            <a href="{{route('pidsr_casechecker_edit', [$case_name, $epi_id])}}" class="btn btn-secondary"><i class="fa fa-cog" aria-hidden="true"></i></a>
                            <a href="{{route('pidsr_laboratory_new')}}?case_id={{$l->edcs_caseid}}&disease={{$case_name}}" class="btn btn-primary"><i class="fa fa-flask"></i></a>
                        </td>
                        @foreach($columns as $c)
                        <td>{{mb_strtoupper($l->$c)}}</td>
                        @endforeach
                        <td class="text-center">
                            @if($l->enabled == 1)
                            <a href="{{route('pidsr_casechecker_action', ['d' => request()->input('case'), 'action' => 'DEL', 'epi_id' => $epi_id])}}" class="btn btn-warning mb-3" onclick="return confirm('Proceed to disable? The record will not be listed anymore after processing.')">Disable</a>
                            @else
                            <a href="{{route('pidsr_casechecker_action', ['d' => request()->input('case'), 'action' => 'ENB', 'epi_id' => $epi_id])}}" class="btn btn-success mb-3" onclick="return confirm('Proceed to enable? The record will return to the official list after processing.')">Enable</a>
                            @endif
                            @if($l->match_casedef == 1)
                            <a href="{{route('pidsr_casechecker_action', ['d' => request()->input('case'), 'action' => 'NOTMATCH_CASEDEF', 'epi_id' => $epi_id])}}" class="btn btn-secondary" onclick="return confirm('Proceed to enable? The record will be marked as NOT MATCH in Case Definition after processing.')">NOT MATCH in CaseDef</a>
                            @else
                            <a href="{{route('pidsr_casechecker_action', ['d' => request()->input('case'), 'action' => 'MATCH_CASEDEF', 'epi_id' => $epi_id])}}" class="btn btn-primary" onclick="return confirm('Proceed to enable? The record will be marked as MATCH in Case Definition after processing.')">MATCH in CaseDef</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<script>
    $('#list_table').dataTable({
        //responsive: true,
        //fixedHeader: true,
        dom: 'QBfritp',
        buttons: [
            'excel', 'copy',
        ],
    });
</script>
@endsection