@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Patient List</b> (Total: {{$list->total()}})</div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPatient">Add Patient</button></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search By SURNAME, NAME | ID" style="text-transform: uppercase;" autocomplete="off" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name / ID</th>
                        <th>Age / Sex</th>
                        <th>Birthdate</th>
                        <th>Street/Purok</th>
                        <th>Barangay</th>
                        <th>City / Province</th>
                        <th>Encoded from Branch</th>
                        <th>Date Encoded / By</th>
                        <th>Date Updated / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $i)
                    <tr>
                        <td class="text-center"><b>{{$list->firstItem() + $ind}}</b></td>
                        <td><b><a href="{{route('pharmacy_view_patient', $i->id)}}">{{$i->getName()}}</a> <small>(#{{$i->id}})</small></b></td>
                        <td class="text-center">{{$i->getAge()}} / {{$i->sg()}}</td>
                        <td class="text-center">{{date('m/d/Y', strtotime($i->bdate))}}</td>
                        <td class="text-center">{{$i->getStreetPurok()}}</td>
                        <td class="text-center">{{$i->address_brgy_text}}</td>
                        <td class="text-center">{{$i->address_muncity_text}}, {{$i->address_province_text}}</td>
                        <td class="text-center">
                            @if(auth()->user()->isAdminPharmacy())
                            <a href="{{route('pharmacy_view_branch', $i->pharmacybranch->id)}}">{{$i->pharmacybranch->name}}</a>
                            @else
                            {{$i->pharmacybranch->name}}
                            @endif
                        </td>
                        <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($i->created_at))}} / {{$i->user->name}}</small></td>
                        <td class="text-center"><small>{{($i->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($i->updated_at)).' / '.$i->getUpdatedBy->name : 'N/A'}}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

<form action="{{route('pharmacy_add_patient')}}" method="GET">
    <div class="modal fade" id="addPatient" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection