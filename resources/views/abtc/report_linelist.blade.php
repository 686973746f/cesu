@extends('layouts.app')

@section('content')
<style>
    #mytable td {
        vertical-align: middle;
    }
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Linelist - Total Count: {{$list->count()}}</div>
                <div><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#halp"><i class="fa-solid fa-circle-question me-2"></i>Help</button></div>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert">
                {{$alt}}
            </div>
            
            <form action="{{route('abtc_report_linelist_index')}}" method="GET">
                <div class="card">
                    <div class="card-header"><b>Filter</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""></label>
                                    <select class="form-control" name="fyear" id="fyear" required>
                                      <option value="" disabled selected>Select Year...</option>
                                      @foreach(range(date('Y'), 2020) as $y)
                                      <option value="{{$y}}" {{(old('fyear', request()->input('fyear')) == $y) ? 'selected': ''}}>{{$y}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""></label>
                                    <select class="form-control" name="vid" id="vid" required>
                                      <option value="" disabled selected>Select Vaccination Site...</option>
                                      @foreach($vslist as $vs)
                                        <option value="{{$vs->id}}" {{(request()->input('vid') == $vs->id || $vs->id == auth()->user()->abtc_default_vaccinationsite_id) ? 'selected' : ''}}>{{$vs->site_name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="mytable">
                    <thead class="text-center thead-light" style="vertical-align: middle;">
                        <tr>
                            <th colspan="4">Registration</th>
                            <th colspan="7">History of Exposure</th>
                            <th colspan="10">Post Exposure Prophylaxis (PEP)</th>
                            <th rowspan="3">Outcome</th>
                            <th rowspan="3">Biting Animal Status <small>(after 14 Days)</small></th>
                            <th rowspan="3">Remarks</th>
                        </tr>
                        <tr>
                            <th>No.</th>.
                            <th>Date</th>
                            <th>Name of Patient</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Date</th>
                            <th>Place <small>(Where biting occured)</small></th>
                            <th>Type of Animal</th>
                            <th>Type</th>
                            <th>Site <small>(Body Parts)</small></th>
                            <th>Category</th>
                            <th>Washing of Bite</th>
                            <th>RIG Date given</th>
                            <th>Route</th>
                            <th>D0</th>
                            <th>D3</th>
                            <th>D7</th>
                            <th>D14 (IM)</th>
                            <th>D28</th>
                            <th>Brand Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $d)
                        <tr class="text-center" style="vertical-align: middle;">
                            <td><a href="{{route('abtc_encode_edit', $d->id)}}">{{$d->case_id}}</a></td>
                            <td>{{date('m/d/Y', strtotime($d->case_date))}}</td>
                            <td><a href="{{route('abtc_patient_edit', ['id' => $d->patient->id])}}">{{$d->patient->getName()}}</a></td>
                            <td><small>{{$d->patient->address_brgy_text}}, {{$d->patient->address_muncity_text}}, {{$d->patient->address_province_text}}</small></td>
                            <td>{{$d->patient->getAge()}}</td>
                            <td>{{$d->patient->sg()}}</td>
                            <td>{{date('m/d/Y', strtotime($d->bite_date))}}</td>
                            <td><small>{{$d->case_location}}</small></td>
                            <td>{{$d->animal_type}}</td>
                            <td>{{$d->bite_type}}</td>
                            <td>{{(!is_null($d->body_site)) ? mb_strtoupper($d->body_site) : 'N/A'}}</td>
                            <td>{{$d->category_level}}</td>
                            <td>{{($d->washing_of_bite == 1) ? 'Y' : 'N'}}</td>
                            <td>{{(!is_null($d->rig_date_given)) ? date('m/d/Y', strtotime($d->rig_date_given)) : 'N/A'}}</td>
                            <td>{{$d->pep_route}}</td>
                            <td>{{date('m/d/Y', strtotime($d->d0_date))}}</td>
                            <td>{{($d->d3_done == 1) ? date('m/d/Y', strtotime($d->d3_date)) : ''}}</td>
                            <td>{{($d->is_booster == 0) ? ($d->d7_done == 1) ? date('m/d/Y', strtotime($d->d7_date)) : '' : 'N/A'}}</td>
                            <td>{{($d->d14_done == 1 && $d->pep_route == 'IM') ? date('m/d/Y', strtotime($d->d14_date)) : 'N/A'}}</td>
                            <td>{{($d->is_booster == 0) ? ($d->d28_done == 1) ? date('m/d/Y', strtotime($d->d28_date)) : '' : 'N/A'}}</td>
                            <td>{{$d->brand_name}}</td>
                            <td>{{$d->outcome}}</td>
                            <td>{{$d->biting_animal_status}}</td>
                            <td><small>{{($d->is_booster == 1) ? 'BOOSTER' : ''}} {{$d->remarks}}</small></td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="24">No Results found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="halp" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id=""><i class="fa-solid fa-circle-question me-2"></i>Help</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p><strong>Registration Number:</strong> (Current Year) - 001 (Chronologic number)</p>
            <p><strong>Registration Date:</strong> Date Patient was first seen, regardless whether patient was given PEP or not.</p>
            <p><strong>Type of Animal:</strong> <b>(PD)</b> Pet Dog, <b>(SD)</b> Stray Dog - Owned or ownerless dogs freely roaming the community. <b>(C)</b> Cat, <b>(O)</b> Others</p>
            <p><strong></strong> <b>(B)</b> Bite and (NB) None Bite, to include all non-biting rabies exposures (earing of raw meat, splattering, kissing and others)</p>
            <p><strong>Outcome:</strong> <b>(C)</b> Completed - Patients received at least days 0, 3 and 7 doses, <b>(INC)</b> Incomplete - Patients who did not receive at least days 0, 3 and 7. <b>(D)</b> Died - Patient who died at whatever cause while on PEOP. <b>(N)</b> None - Category II and III exposures who did not receive any of the TCV doses.</p>
            <p><strong>Status of Biting Animal:</strong> <b>(A)</b> Alive, <b>(D)</b> Dead and <b>(L)</b> Lost - Animal not available for observation for 14 Days.</p>
        </div>
        </div>
    </div>
</div>
@endsection