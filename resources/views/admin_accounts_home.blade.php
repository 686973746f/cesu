@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>Admin Accounts</div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createadmin">Add Admin Account</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lists as $list)
                            <tr>
                                <td scope="row">{{$list->name}}</td>
                                <td>{{$list->email}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <form action="" method="POST">
        @csrf
        <div class="modal fade" id="createadmin" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Admin Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="">Admin Type</label>
                          <select class="form-control" name="" id="">
                                <option value="" disabled selected>Choose...</option>
                                <option value="1">Super Admin</option>
                                <option value="2">Semi-Admin (for Encoders)</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Input your password</label>
                          <input type="password"
                            class="form-control" name="" id="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection