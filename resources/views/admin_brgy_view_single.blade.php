@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('adminpanel.brgy.update', ['id' => $data->id])}}" method="POST">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">Edit Barangay Data</div>
                    <div class="card-body">
                        <div class="form-group">
                          <label for="brgyName"><span class="text-danger font-weight-bold">*</span>Barangay Name</label>
                          <input type="text" class="form-control" name="brgyName" id="brgyName" value="{{old('brgyName', $data->brgyName)}}" required>
                        </div>
                        <div class="form-group">
                          <label for="displayInList"><span class="text-danger font-weight-bold">*</span>Status</label>
                          <select class="form-control" name="displayInList" id="displayInList" required>
                            <option value="0" {{(old('displayInList', $data->displayInList) == 0) ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{(old('displayInList', $data->displayInList) == 1) ? 'selected' : ''}}>Enabled</option>
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="city_id"><span class="text-danger font-weight-bold">*</span>Assign to City</label>
                            <select class="form-control" name="city_id" id="city_id" required>
                                @foreach($city_list as $city)
                                <option value="{{$city->id}}" {{($city->id == old('city_id', $data->city_id)) ? 'selected' : ''}}>{{$city->cityName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimatedPopulation">Estimated Population <small>(Optional)</small></label>
                                    <input type="number" class="form-control" name="estimatedPopulation" id="estimatedPopulation" value="{{old('estimatedPopulation', $data->estimatedPopulation)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="dilgCustCode">DILG Code <small>(Optional)</small></label>
                                  <input type="text" class="form-control" name="dilgCustCode" id="dilgCustCode" value="{{old('dilgCustCode', $data->dilgCustCode)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>List of Users in Brgy. {{$data->brgyName}}</div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add User</button></div>
            </div>
        </div>    
        <div class="card-body">
            @if($account_list->count() == 0)
            <p class="text-center">No results found.</p>
            @else
            <table class="table table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($account_list as $key => $account)
                    <tr class="text-center">
                        <td scope="row" class="text-center">{{$loop->iteration}}</td>
                        <td>{{$account->name}}</td>
                        <td>{{$account->email}}</td>
                        <td><a href="{{route('adminpanel.brgy.view.user', ['brgy_id' => $data->id, 'user_id' => $account->id])}}" class="btn btn-primary btn-small"><i class="fas fa-file-alt mr-2"></i>Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<form action="{{route('adminpanel.brgyCode.store', ['brgy_id' => $data->id])}}" method="POST">
    @csrf
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User in Brgy. {{$data->brgyName}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Note: A Referral Code URL will be given to you after clicking [Proceed] button. You will need to share it to the auhtorized personnel in order to register using the Referral Code. Type your password and click [Proceed] to continue.</p>
                    <hr>
                    <div class="form-group">
                      <label for="pw">Input your Password</label>
                      <input type="password" class="form-control" name="pw" id="pw" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Proceed</button>
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
            <div class="modal-body text-center">
                <p>You can now share this referral code to the authorized personnel in order to gain access inside the web system.</p>
                <hr>
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