@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-right">
        <a href="{{route('vaxcert_vquery_templatemaker')}}" class="btn btn-secondary mb-3">Template Maker</a>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#vquery"><i class="fa fa-search mr-2" aria-hidden="true"></i>Internal Vaccinee Query</button>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>VaxCert Concerns</b> (Total: {{$list->total()}})</div>
                <div>
                    @if(request()->input('viewcomplete'))
                    Currently Viewing <b class="text-success">COMPLETED</b> List <i>(Oldest to Newest)</i>. <a href="{{route('vaxcert_home')}}" class="btn btn-warning ml-3">Show Pending</a>
                    @else
                    Currently Viewing <b class="text-warning">PENDING</b> List <i>(Newest to Oldest)</i>. <a href="{{route('vaxcert_home')}}?viewcomplete=1" class="btn btn-success ml-3">Show Completed</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search by Name / Ticket ID" style="text-transform: uppercase;" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if($list->count() != 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Ticket ID / Code</th>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Age / Sex</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Concern Type</th>
                            <th>Category</th>
                            <th>Travel Type</th>
                            <th>Date Submitted</th>
                            @if(request()->input('viewcomplete'))
                            <th>Status</th>
                            <th>Processed by / At</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $d)
                        @php
                        if($d->status == 'PENDING') {
                            $stext = 'text-warning';
                        }
                        else if($d->status == 'COMPLETE') {
                            $stext = 'text-warning';
                        }
                        @endphp
                        <tr>
                            <td class="text-center">{{$list->firstItem() + $key}}</td>
                            <td class="text-center">#{{$d->id}} - {{$d->sys_code}}</td>
                            <td><b><a href="{{route('vaxcert_viewpatient', $d->id)}}">{{$d->getName()}}</a></b></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}}</td>
                            <td class="text-center">{{$d->getAge()}} / {{$d->gender}}</td>
                            <td><small>{{$d->getAddress()}}</small></td>
                            <td class="text-center">{{$d->contact_number}}</td>
                            <td class="text-center">{{(!is_null($d->email)) ? $d->email : 'N/A'}}</td>
                            <td class="text-center">{{$d->concern_type}}</td>
                            <td class="text-center">{{$d->category}}</td>
                            <td class="text-center">{{($d->use_type == 'ABROAD') ? 'ABROAD - '.$d->passport_no : 'LOCAL'}}</td>
                            <td class="text-center"><small>{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}}</small></td>
                            @if(request()->input('viewcomplete'))
                            <td class="text-center"><b>{{$d->status}}</b></td>
                            <td class="text-center">{{$d->getProcessedBy()}} / <small>{{date('m/d/Y (D) h:i A', strtotime($d->updated_at))}}</small></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center">No pending records found. Come back later.</p>
            @endif
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="{{route('vaxcert_report')}}" class="btn btn-info text-white">Report</a>
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
                      <input type="text" name="lname" id="lname" class="form-control" minlength="1" maxlength="50" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" name="fname" id="fname" class="form-control" minlength="1" maxlength="50" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label for="">Birthdate <i>(Optional)</i></label>
                        <input type="date" class="form-control" name="bdate" id="bdate" max="{{date('Y-m-d')}}">
                    </div>
                    <div class="alert alert-info" role="alert">
                        <b class="text-danger">Note:</b> Internal Vaccinee Query ONLY displays data of patients Vaccinated in City of General Trias, Cavite. Other Vaccination sites in Other Cities/Provinces are not included.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection