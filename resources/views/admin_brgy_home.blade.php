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
                <div>Barangay Accounts</div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBrgyModal">Add Barangay</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th>Number of Users</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lists as $list)
                    <tr>
                        <td scope="row">{{$list->brgyName}}</td>
                        <td>{{$users->where('brgy_id', $list->id)->count()}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                          @foreach($lists as $list)
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


@endsection