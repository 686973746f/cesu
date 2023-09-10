@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <a href="{{route('vaxcert_vquery_templatemaker')}}" class="btn btn-secondary mb-3">Template Maker</a>
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
                    <table class="table table-bordered table-striped" style="white-space: nowrap" id="maintbl">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th>Vaccination Date</th>
                                <th>Vaccine</th>
                                <th>Batch No.</th>
                                <th>Bakuna Center Code</th>
                                <th>Vaccinator Name</th>
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
                                <th>Adverse Event</th>
                                <th>Adverse Event Condition</th>
                                <th>Row Hash</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($d as $a)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$a->last_name}}&firstname={{$a->first_name}}&birthdate={{date('Y-m-d', strtotime($a->birthdate))}}{{(!is_null($a->suffix)) ? '&suffix='.$a->suffix : ''}}" target="_blank" class="dropdown-item">Search in VAS</a>
                                            <a href="https://vaslinelist.dict.gov.ph/vaxcert/correction?lastname={{$a->last_name}}&firstname={{$a->first_name}}" class="dropdown-item" target="_blank">Search in CORRECTION Request</i></a>
                                            <a href="https://vaslinelist.dict.gov.ph/vaxcert/not-found?lastname={{$a->last_name}}&firstname={{$a->first_name}}" class="dropdown-item" target="_blank">Search in NOT FOUND Requests</i></a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{route('vaxcert_vquery_templatemaker', ['use_id' => $a->id])}}" class="dropdown-item">Load into Template Maker</a>
                                            <a href="{{route('vaxcert_vquery_template', $a->id)}}" class="dropdown-item"><i class="fa fa-file-excel mr-2" aria-hidden="true"></i>Download (.XLSX)</a>
                                        </div>
                                    </div>
                                </td>
                                @if($enyecheck && mb_strpos(request()->input('lname'), 'Ñ') == mb_strpos($a->last_name, 'N'))
                                <td class="bg-danger">{{$a->last_name}}</td>
                                @else
                                <td>{{$a->last_name}}</td>
                                @endif
                                <td>{{$a->first_name}}</td>
                                <td>{{$a->middle_name}}</td>
                                <td>{{$a->suffix}}</td>
                                <td>{{date('m/d/Y', strtotime($a->birthdate))}} <small class="text-muted">({{$a->getAge()}} y.o)</small></td>
                                <td>{{$a->sex}}</td>
                                <td class="{{$a->doseCheckColor()}}">{{date('m/d/Y', strtotime($a->vaccination_date))}} {{$a->showDoseType()}}</td>
                                <td>{{$a->vaccine_manufacturer_name}}</td>
                                <td>{{$a->batch_number}}</td>
                                <td><small>{{$a->bakuna_center_cbcr_id}}</small></td>
                                <td>{{$a->vaccinator_name}}</td>
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
            @if($paginate)
            <div class="card-footer">
                <div class="pagination justify-content-center mt-3">
                    {{$d->appends(request()->input())->links()}}
                </div>
            </div>
            @endif
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
                          <label for="">Last Name</label>
                          <input type="text" name="lname" id="lname" class="form-control" minlength="1" maxlength="50" style="text-transform: uppercase;" value="{{request()->input('lname')}}">
                        </div>
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control" minlength="1" maxlength="50" style="text-transform: uppercase;" value="{{request()->input('fname')}}">
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

<script>
    $('#maintbl').DataTable({
        dom: 'frti',
        iDisplayLength: -1,
    });
</script>
@endsection