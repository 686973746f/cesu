@extends('layouts.app')

@section('content')
<div class="container">
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
            <p class="mb-0">{{$error}}</p>
            @endforeach
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="font-weight-bold">Barangay Accounts</div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBrgyModal">Add Barangay</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('adminpanel.brgy.index')}}" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Barangay / ID" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if(request()->input('q'))
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$lists->count()}} {{Str::plural('result', $lists->count())}}.
            </div>
            @endif
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>#</th>
                        <th>Barangay</th>
                        <th>Number of Users</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lists as $key => $list)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">{{$lists->firstItem() + $key}}</td>
                        <td style="vertical-align: middle;">{{$list->brgyName}}</td>
                        <td class="text-center" style="vertical-align: middle;">{{number_format($users->where('brgy_id', $list->id)->count())}}</td>
                        <td class="text-center">
                            @if($users->where('brgy_id', $list->id)->count())
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_{{$list->id}}"><i class="fa fa-eye mr-2" aria-hidden="true"></i>View</button>
                            @else
                            <button type="button" class="btn btn-primary" disabled><i class="fa fa-eye mr-2" aria-hidden="true"></i>View</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$lists->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

@foreach($lists as $list)
    @if($users->where('brgy_id', $list->id)->count())
    <div class="modal fade" id="modal_{{$list->id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Existing Users in {{$list->brgyName}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->where('brgy_id', $list->id) as $item)
                            <tr>
                                <td scope="row" style="vertical-align: middle;">{{$item->name}}</td>
                                <td style="vertical-align: middle;">{{$item->email}}</td>
                                <td style="vertical-align: middle;" class="{{($item->enabled == 1) ? 'text-success' : 'text-danger'}} text-center font-weight-bold">{{($item->enabled == 1) ? 'Enabled': 'Disabled'}}</td>
                                <td style="vertical-align: middle;" class="text-center">
                                    <button type="button" class="btn btn-primary">Disable</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<form action="{{route('adminpanel.brgy.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="modal fade" id="addBrgyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Barangay</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="brgyName">Barangay Name</label>
                      <input type="text" name="brgyName" id="brgyName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('adminpanel.brgyCode.store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Barangay User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="brgyId">Add User in Barangay</label>
                      <select class="form-control" name="brgyId" id="brgyId" required>
                          <option value="" disabled selected>Choose...</option>
                          @foreach($allBrgy as $list)
                            <option value="{{$list->id}}">{{$list->brgyName}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@if(session('process') == 'createCode')
    <div class="modal fade" id="showCode" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Barangay Referral Code has been created!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <p>You can now share this referral code to the respective user to gain access inside the website.</p>
                    <p></p>
                    <p><code>{{route('rcode.check')}}?refCode={{session('bCode')}}</code></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#showCode').modal('show');  
        });
    </script>
@endif
<script>
    $(document).ready(function () {
        $('#brgyId').select2({
            theme: "bootstrap",
        });
    });
</script>
@endsection