@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Fireworks-Related Injury (FWRI) - Home</b> (Total: {{$list->count()}})</div>
                    <div>
                        <a href="{{route('fwri_export')}}" class="btn btn-success">Export</a>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#filterBtn">Filter</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reportMod">Report</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($list->count() != 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mainTbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date Submitted</th>
                                <th>Reporting Facility</th>
                                <th>Patient Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Street/Purok</th>
                                <th>Barangay</th>
                                <th>City/Municipality</th>
                                <th>Contact Number</th>
                                <th>Date Reported</th>
                                <th>Involvement Type</th>
                                <th>Nature of Injury</th>
                                <th>Type of Injury</th>
                                <th>Diagnosis</th>
                                <th>Anatomical Location</th>
                                <th>Injury Occurred at</th>
                                <th>Name of Firecracker</th>
                                <th>Fireworks Illegal</th>
                                <th>Date of Injury</th>
                                <th>Liquor Intoxication</th>
                                <th>Treatment Given</th>
                                <th>Disposition After Consultation</th>
                                <th>Disposition After Admission</th>
                                <th>Reporter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $d)
                            <tr>
                                <td class="text-center"><b>{{$ind+1}}</b></td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                <td class="text-center"><small>{{$d->hospital_name}}</small></td>
                                <td><b><a href="{{route('fwri_view', $d->id)}}">{{$d->getName()}}</a></b></td>
                                <td class="text-center">{{$d->age_years}}</td>
                                <td class="text-center">{{$d->sg()}}</td>
                                <td class="text-center">{{$d->getStreetPurok()}}</td>
                                <td class="text-center">{{$d->address_brgy_text}}</td>
                                <td class="text-center">{{$d->address_muncity_text}}</td>
                                <td class="text-center">{{$d->contact_number}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($d->report_date))}}</td>
                                <td class="text-center">{{$d->involvement_type}}</td>
                                <td class="text-center">{{$d->nature_injury}}</td>
                                <td class="text-center">{{$d->iffw_typeofinjury}}</td>
                                <td class="text-center">{{$d->complete_diagnosis}}</td>
                                <td class="text-center">{{$d->anatomical_location}}</td>
                                <td class="text-center"><small>{{$d->getInjuryAddStr()}}</small></td>
                                <td class="text-center">{{$d->firework_name}}</td>
                                <td class="text-center">{{$d->firework_illegal}}</td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->injury_date))}}</td>
                                <th class="text-center">{{$d->liquor_intoxication}}</th>
                                <th class="text-center">{{$d->treatment_given}}</th>
                                <th class="text-center">{{$d->disposition_after_consultation}}</th>
                                <th class="text-center">{{$d->disposition_after_admission}}</th>
                                <td class="text-center">{{$d->reported_by}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">No results found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportMod" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <a href="{{route('fwri_report')}}" class="btn btn-primary btn-block">Open Report Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="filterBtn" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="">Select Year</label>
                          <select class="form-control" name="select_year" id="select_year" required>
                            @foreach(range(date('Y'), 2019) as $y)
                            <option value="{{$y}}" {{(request()->input('select_year') == $y) ? 'selected' : ''}}>{{$y}} (Dec. 1, {{$y}} to Jan. 10, {{$y+1}})</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#mainTbl').dataTable({
            iDisplayLength: -1,
            dom: 'Bfrit',
            buttons: [
                {
                    extend: 'excel',
                    title: '',
                },
                'copy',
            ],
        });
    </script>
@endsection