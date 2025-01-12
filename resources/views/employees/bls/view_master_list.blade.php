@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>BLS/SFA Masterlist</b></div>
                <div><a href="{{route('bls_home_batches')}}" class="btn btn-primary">Switch to Batches View</a></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Type of Provider</th>
                            <th>Position</th>
                            <th>Institution/Agency</th>
                            <th>Status of Employment</th>
                            <th>Date of Birth</th>
                            <th>Address</th>
                            <th>Contact Details</th>
                            <th>Email Address</th>
                            <th>Code Name</th>
                            <th>ID No.</th>
                            <th>Expiration Date</th>
                            <th>Last Training Date</th>
                            <th>Last Training Year</th>
                            <th>For Refresher</th>
                            <th>Picture</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $d)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$d->ifForRefresher()}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#mainTbl').DataTable();
    });
</script>
@endsection