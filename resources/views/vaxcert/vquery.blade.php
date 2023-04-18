@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#vquery"><i class="fa fa-search mr-2" aria-hidden="true"></i>Search Again</button>
        </div>
        <div class="card">
            <div class="card-header"><b>Internal Vaccinee Query</b></div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b class="text-danger">Note:</b> Internal Vaccinee Query ONLY displays data of patients Vaccinated in City of General Trias, Cavite. Other Vaccination sites in Other Cities/Provinces are not included.
                </div>
                @if($d->count() != 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="white-space: nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th>Vaccination Date</th>
                                <th>Vaccine</th>
                                <th>Category</th>
                                <th>Comorbidity</th>
                                <th>Unique Person ID</th>
                                <th>PWD</th>
                                <th>Indigenous Member</th>
                                <th>Contact No.</th>
                                <th>Guardian Name</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>Municipality</th>
                                <th>Barangay</th>
                                <th>Batch No.</th>
                                <th>Lot No.</th>
                                <th>Bakuna Center Code</th>
                                <th>Vaccinator Name</th>
                                <th>1st Dose</th>
                                <th>2nd Dose</th>
                                <th>Booster 1</th>
                                <th>Booster 2</th>
                                <th>Adverse Event</th>
                                <th>Adverse Event Condition</th>
                                <th>Row Hash</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($d as $a)
                            <tr>
                                <td>{{$a->last_name}}</td>
                                <td>{{$a->first_name}}</td>
                                <td>{{$a->middle_name}}</td>
                                <td>{{$a->suffix}}</td>
                                <td>{{date('m/d/Y', strtotime($a->birthdate))}}</td>
                                <td>{{$a->sex}}</td>
                                <td>{{date('m/d/Y', strtotime($a->vaccination_date))}}</td>
                                <td>{{$a->vaccine_manufacturer_name}}</td>
                                <td>{{$a->category}}</td>
                                <td>{{$a->comorbidity}}</td>
                                <td>{{$a->unique_person_id}}</td>
                                <td>{{$a->pwd}}</td>
                                <td>{{$a->indigenous_member}}</td>
                                <td>{{$a->contact_no}}</td>
                                <td>{{$a->guardian_name}}</td>
                                <td>{{$a->region}}</td>
                                <td>{{$a->province}}</td>
                                <td>{{$a->muni_city}}</td>
                                <td>{{$a->barangay}}</td>
                                <td>{{$a->batch_number}}</td>
                                <td>{{$a->lot_no}}</td>
                                <td>{{$a->bakuna_center_cbcr_id}}</td>
                                <td>{{$a->vaccinator_name}}</td>
                                <td>{{$a->first_dose}}</td>
                                <td>{{$a->second_dose}}</td>
                                <td>{{$a->additional_booster_dose}}</td>
                                <td>{{$a->second_additional_booster_dose}}</td>
                                <td>{{$a->adverse_event}}</td>
                                <td>{{$a->adverse_event_condition}}</td>
                                <td><small>{{$a->row_hash}}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">No Results Found.</p>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('vaxcert_vquery')}}" method="GET">
        <div class="modal fade" id="vquery" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Internal Vaccinee Query</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for=""><span class="text-danger font-weight-bold">*</span>Last Name</label>
                          <input type="text" name="lname" id="lname" class="form-control" minlength="2" maxlength="50" style="text-transform: uppercase;" value="{{request()->input('lname')}}" required>
                        </div>
                        <div class="form-group">
                            <label for=""><span class="text-danger font-weight-bold">*</span>First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control" minlength="2" maxlength="50" style="text-transform: uppercase;" value="{{request()->input('fname')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Birthdate <i>(Optional)</i></label>
                            <input type="date" class="form-control" name="bdate" id="bdate" max="{{date('Y-m-d')}}" value="{{request()->input('bdate')}}">
                        </div>
                        <div class="alert alert-info" role="alert">
                            <b class="text-danger">Note:</b> Internal Vaccinee Query ONLY displays data of patients Vaccinated in City of General Trias, Cavite. Other Vaccination sites in Other Cities/Provinces are not included.
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