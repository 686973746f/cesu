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
                        <a href="{{route('syndromic_diseasechecker_specific', ['abd'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Bloody Diarrhea</h4>
                            <h5 class="text-warning">{{$abd_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['aefi'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>AEFI</h4>
                            <h5 class="text-warning">{{$aefi_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['aes'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Encephalitis Syndrome (AES)</h4>
                            <h5 class="text-warning">{{$aes_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['afp'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Flaccid Paralysis</h4>
                            <h5 class="text-warning">{{$afp_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['ahf'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Hemorrhagic Fever</h4>
                            <h5 class="text-warning">{{$ahf_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['ames'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>AMES</h4>
                            <h5 class="text-warning">{{$ames_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['anthrax'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Anthrax</h4>
                            <h5 class="text-warning">{{$anthrax_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['chikv'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Chikungnuya</h4>
                            <h5 class="text-warning">{{$chikv_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['cholera'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Cholera</h4>
                            <h5 class="text-warning">{{$cholera_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['dengue'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Dengue</h4>
                            <h5 class="text-warning">{{$dengue_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['diph'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Diptheria</h4>
                            <h5 class="text-warning">{{$diph_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['hepatitis'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Acute Viral Hepatitis</h4>
                            <h5 class="text-warning">{{$hepatitis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['hfmd'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Hand Foot & Mouth Disease (HFMD)</h4>
                            <h5 class="text-warning">{{$hfmd_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['influenza'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Influenza-Like Illness (ILI)</h4>
                            <h5 class="text-warning">{{$influenza_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['leptospirosis'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Leptospirosis</h4>
                            <h5 class="text-warning">{{$leptospirosis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['malaria'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Malaria</h4>
                            <h5 class="text-warning">{{$malaria_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['measles'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Measles</h4>
                            <h5 class="text-warning">{{$measles_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['meningitis'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Meningitis</h4>
                            <h5 class="text-warning">{{$meningitis_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['meningo'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Meningococcal Disease</h4>
                            <h5 class="text-warning">{{$meningo_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['nnt'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Non-Neonatal Tetanus</h4>
                            <h5 class="text-warning">{{$nnt_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['nt'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Neonatal Tetanus</h4>
                            <h5 class="text-warning">{{$nt_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['pert'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Pertussis</h4>
                            <h5 class="text-warning">{{$pert_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['psp'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Paralytic Shellfish Poisoning</h4>
                            <h5 class="text-warning">{{$psp_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['rabies'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Rabies</h4>
                            <h5 class="text-warning">{{$rabies_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['rotavirus'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>RotaVirus</h4>
                            <h5 class="text-warning">{{$rotavirus_count}}</h5>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{route('syndromic_diseasechecker_specific', ['typhoid'])}}?db={{request()->input('db')}}&mw={{request()->input('mw')}}&year={{request()->input('year')}}" class="btn btn-primary btn-block">
                            <h4>Typhoid</h4>
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