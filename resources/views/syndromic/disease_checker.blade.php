@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Disease Checker List</b></div>
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filteropt">Filtering Options</button></div>
                </div>
            </div>
            <div class="card-body">
                @if(request()->input('db') && request()->input('year'))
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=ABD&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Bloody Diarrhea (ABD)</h4>
                            <h5 class="text-warning">{{$abd_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=AEFI&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>AEFI (AEFI)</h4>
                            <h5 class="text-warning">{{$aefi_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=AES&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Encephalitis Syndrome (AES)</h4>
                            <h5 class="text-warning">{{$aes_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=AFP&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Flaccid Paralysis (AFP)</h4>
                            <h5 class="text-warning">{{$afp_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=AHF&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Hemorrhagic Fever (AHF)</h4>
                            <h5 class="text-warning">{{$ahf_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=AMES&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>AMES</h4>
                            <h5 class="text-warning">{{$ames_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=ANTHRAX&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Anthrax</h4>
                            <h5 class="text-warning">{{$anthrax_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=CHIKV&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Chikungnuya</h4>
                            <h5 class="text-warning">{{$chikv_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=CHOLERA&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Cholera</h4>
                            <h5 class="text-warning">{{$cholera_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=DENGUE&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Dengue</h4>
                            <h5 class="text-warning">{{$dengue_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=DIPH&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Diptheria</h4>
                            <h5 class="text-warning">{{$diph_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=HEPATITIS&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Viral Hepatitis</h4>
                            <h5 class="text-warning">{{$hepatitis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=HFMD&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Hand Foot & Mouth Disease (HFMD)</h4>
                            <h5 class="text-warning">{{$hfmd_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=INFLUENZA&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Influenza-Like Illness (ILI)</h4>
                            <h5 class="text-warning">{{$influenza_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=LEPTOSPIROSIS&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Leptospirosis</h4>
                            <h5 class="text-warning">{{$leptospirosis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=MALARIA&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Malaria</h4>
                            <h5 class="text-warning">{{$malaria_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=MEASLES&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Measles</h4>
                            <h5 class="text-warning">{{$measles_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=MENINGITIS&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Meningitis</h4>
                            <h5 class="text-warning">{{$meningitis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=MENINGO&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Meningococcal Disease</h4>
                            <h5 class="text-warning">{{$meningo_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=NNT&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Non-Neonatal Tetanus (NNT)</h4>
                            <h5 class="text-warning">{{$nnt_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=NT&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Neonatal Tetanus (NT)</h4>
                            <h5 class="text-warning">{{$nt_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=PERT&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Pertussis</h4>
                            <h5 class="text-warning">{{$pert_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=PSP&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Paralytic Shellfish Poisoning (PSP)</h4>
                            <h5 class="text-warning">{{$psp_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=RABIES&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Rabies</h4>
                            <h5 class="text-warning">{{$rabies_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=ROTAVIRUS&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>RotaVirus</h4>
                            <h5 class="text-warning">{{$rotavirus_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route($route)}}?case=TYPHOID&db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Typhoid and Paratyphoid Fever</h4>
                            <h5 class="text-warning">{{$typhoid_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                    </div>
                    <div class="col-md-3 mb-3">
                    </div>
                </div>
                @else

                @endif
                
            </div>
        </div>
    </div>
    
    <form action="" method="GET">
        <div class="modal fade" id="filteropt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filtering Options</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="db">Search in</label>
                          <select class="form-control" name="db" id="db" required>
                            <option value="OPD">OPD (Suspected Diseases List)</option>
                            <option value="PIDSR">PIDSR Database</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="year">Select Year</label>
                          <select class="form-control" name="year" id="year" required>
                            <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach(range(date('Y'), 2015) as $y)
                            <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="mw">Select Morbidity Week (Optional)</label>
                          <input type="number" min="1" max="53" class="form-control" name="mw" id="mw">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection