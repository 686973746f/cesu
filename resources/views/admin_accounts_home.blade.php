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
    
    <form action="{{route('adminpanel.account.create')}}" method="POST">
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
                        @if(session('modalstatus'))
                        <div class="alert alert-{{session('statustype')}}" role="alert">
                            <p class="mb-0">{{session('modalstatus')}}</p>
                        </div>
                        @endif
                        <div class="form-group">
                          <label for="adminType">Admin Type</label>
                          <select class="form-control" name="adminType" id="adminType" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="1">Super Admin</option>
                                <option value="2">Semi-Admin (for Encoders)</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="pw">Input your password</label>
                          <input type="password"
                            class="form-control" name="pw" id="pw" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    @if(session('process'))
    <div class="modal fade" id="showCode" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admin Account Referral Code has been created!</h5>
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

    @if(session('modalstatus'))
    <script>
        $(document).ready(function () {
            $('#createadmin').modal('show');
        });
    </script>
    @endif
@endsection