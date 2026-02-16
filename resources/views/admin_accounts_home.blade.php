@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Admin Accounts ({{number_format($lists->total())}})</div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createadmin"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add Admin Account</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center bg-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lists as $key => $list)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{$lists->firstItem() + $key}}</td>
                                    <td style="vertical-align: middle;"><a href="{{route('admin_account_view', ['id' => $list->id])}}">{{$list->name}}</a></td>
                                    <td style="vertical-align: middle;">{{$list->email}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{($list->isAdmin == 1) ? 'Admin' : 'Encoder'}}</td>
                                    <td class="text-center {{($list->enabled == 1) ? 'text-success' : 'text-danger'}} font-weight-bold" style="vertical-align: middle;">{{($list->enabled == 1) ? 'Enabled': 'Disabled'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <form action="{{route('admin_account_reset_password', [$list->id])}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to reset the password of {{$list->name}}?');"><i class="fa fa-key" aria-hidden="true"></i> Reset Password</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{$lists->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('admin_account_create')}}" method="POST">
        @csrf
        <div class="modal fade" id="createadmin" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create User Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="name"><b class="text-danger">*</b>Name</label>
                          <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase" required>
                        </div>
                        <div class="form-group">
                          <label for="email"><b class="text-danger">*</b>Email</label>
                          <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="itr_facility_id"><b class="text-danger">*</b>Link to Consultation Facility ID</label>
                          <select class="form-control" name="itr_facility_id" id="itr_facility_id" required>
                            <option value="" disabled {{(is_null(old('itr_facility_id'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach($facility_list as $fi)
                            <option value="{{$fi->id}}" {{($fi->id == 10886) ? 'selected' : ''}}>{{mb_strtoupper($fi->facility_name)}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="etcl_bhs_id">eTCL BHS ID</label>
                            <select class="form-control" name="etcl_bhs_id" id="etcl_bhs_id">
                              <option value="" disabled {{(is_null(old('etcl_bhs_id'))) ? 'selected' : ''}}>Choose...</option>
                              @foreach($facility_list as $fi)
                              <option value="{{$fi->id}}">{{mb_strtoupper($fi->facility_name)}} (#{{$fi->id}})</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="switch_bhs_list">Switch BHS List (For multiple BHS Handler)</label>
                          <input type="text" class="form-control" name="switch_bhs_list" id="switch_bhs_list" value="{{old('switch_bhs_list')}}">
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="itr_doctor_id">Set Default Consultation Doctor</label>
                          <select class="form-control" name="itr_doctor_id" id="itr_doctor_id">
                            <option value="">N/A</option>
                            @foreach($doctors_list as $di)
                            <option value="{{$di->id}}">{{mb_strtoupper($di->doctor_name)}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="pharmacy_branch_id">Link to Pharmacy Branch</label>
                          <select class="form-control" name="pharmacy_branch_id" id="pharmacy_branch_id">  
                            <option value="">N/A</option>
                            @foreach($pharmacy_branches as $pi)
                            <option value="{{$pi->id}}">{{mb_strtoupper($pi->name)}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="abtc_default_vaccinationsite_id">Link to ABTC Facility</label>
                          <select class="form-control" name="abtc_default_vaccinationsite_id" id="abtc_default_vaccinationsite_id">
                            <option value="">N/A</option>
                            @foreach($abtc_list as $ai)
                            <option value="{{$ai->id}}">{{mb_strtoupper($ai->site_name)}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="form-group" id="perms_div">
                            <label for="permission_list"><b class="text-danger">*</b>Permission List</label>
                            <select class="form-control" name="permission_list[]" id="permission_list" required multiple>
                                @foreach($perm_list as $b)
                                <option value="{{$b}}" {{(in_array($b, explode(",", old('permission_list')))) ? 'selected': ''}}>{{$b}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#permission_list').select2({
            theme: "bootstrap",
            dropdownParent: $('#perms_div'),
        });
    </script>
@endsection