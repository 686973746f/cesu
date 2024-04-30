@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>EDCS Reportable Cases Barangay Dashboard (BRGY. {{session('brgyName')}})</b></div>
                    <div>
                        <form action="">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filterModal">Change Year</button>
                        <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Abd')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Acute Bloody Diarhhea</h4>
                                    <h4 class="text-warning">{{$abd_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Afp')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Acute Flaccid Paralysis</h4>
                                    <h4 class="text-warning">{{$afp_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Ames')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Acute Meningitis Encephalitis (AMES)</h4>
                                    <h4 class="text-warning">{{$ames_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Hepatitis')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Acute Viral Hepatitis</h4>
                                    <h4 class="text-warning">{{$hepa_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Chikv')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Chikungunya Viral Disease</h4>
                                    <h4 class="text-warning">{{$chikv_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Cholera')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Cholera</h4>
                                    <h4 class="text-warning">{{$cholera_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Dengue')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Dengue</h4>
                                    <h4 class="text-warning">{{$dengue_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Diph')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Diphtheria</h4>
                                    <h4 class="text-warning">{{$diph_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Hfmd')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Hand, Foot & Mouth Disease</h4>
                                    <h4 class="text-warning">{{$hfmd_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Influenza')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Influenza-like Illness</h4>
                                    <h4 class="text-warning">{{$ili_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Leptospirosis')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Leptospirosis</h4>
                                    <h4 class="text-warning">{{$lepto_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Measles')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Measles</h4>
                                    <h4 class="text-warning">{{$measles_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Meningo')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Meningococcal Disease</h4>
                                    <h4 class="text-warning">{{$meningo_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Nt')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Neonatal Tetanus</h4>
                                    <h4 class="text-warning">{{$nt_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Nnt')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Non-Neonatal Tetanus</h4>
                                    <h4 class="text-warning">{{$nnt_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Pert')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Pertussis</h4>
                                    <h4 class="text-warning">{{$pert_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Rabies')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Rabies</h4>
                                    <h4 class="text-warning">{{$rabies_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Rotavirus')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Rotavirus</h4>
                                    <h4 class="text-warning">{{$rotavirus_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'SevereAcuteRespiratoryInfection')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Severe Acute Respiratory Infection (SARI)</h4>
                                    <h4 class="text-warning">{{$sari_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('edcs_barangay_view_list', 'Typhoid')}}">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h4 class="text-white">Typhoid and Paratyphoid Fever</h4>
                                    <h4 class="text-warning">{{$typhoid_count}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Year</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach(range(date('Y'), 2015) as $y)
                                <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection