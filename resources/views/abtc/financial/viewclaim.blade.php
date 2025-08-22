@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>View Claim No.</b></div>
            <form action="">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                      <label for="ics_claims_status">Claim Status</label>
                      <select class="form-control" name="ics_claims_status" id="ics_claims_status" required>
                        <option></option>
                        <option value="FOR UPLOADING">For Uploading</option>
                        <option value="PROCESSING">Processing</option>
                        <option value="RTH">RTH/For Compliance</option>
                        <option value="DENIED">Denied</option>
                        <option value="PAID">Paid</option>
                      </select>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </form>
            
        </div>
    </div>
@endsection