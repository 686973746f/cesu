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
            <div class="alert alert-info" role="alert">
                <h3>Demographics</h3>
                <div class="row">
                    <div class="col-md-4">
                        <h5>Total Trained:</h5>
                        <h5>Male:</h5>
                        <h5>Female:</h5>
                    </div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Type of Provider</th>
                            <th>Position</th>
                            <th>Institution/Agency</th>
                            <th>Status of Employment</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Code Name</th>
                            <th>ID No.</th>
                            <th>Expiration Date</th>
                            <th>Last Training Batch</th>
                            <th>Last Training Date</th>
                            <th>Last Training Year</th>
                            <th>For Refresher</th>
                            <th>Picture</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $d)
                        @if($d->getLastTrainingData()->bls_finalremarks == 'P')
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td>{{$d->getName()}}</td>
                            <td class="text-center">{{$d->getAge()}}</td>
                            <td class="text-center">{{$d->gender}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($d->bdate)->format('m/d/Y')}}</td>
                            <td class="text-center">{{$d->provider_type}}</td>
                            <td class="text-center">{{$d->position}}</td>
                            <td class="text-center">{{$d->institution}}</td>
                            <td class="text-center">{{$d->employee_type}}</td>
                            <td>{{$d->getAddress()}}</td>
                            <td class="text-center">{{$d->contact_number}}</td>
                            <td class="text-center">{{$d->email}}</td>
                            <td class="text-center">{{$d->codename}}</td>
                            <td class="text-center">{{($d->getLastTrainingData()) ? $d->getLastTrainingData()->bls_id_number : 'N/A'}}</td>
                            <td class="text-center">{{($d->getLastTrainingData() && $d->getLastTrainingData()->bls_expiration_date) ? date('m/d/Y', strtotime($d->getLastTrainingData()->bls_expiration_date)) : 'N/A'}}</td>
                            <td class="text-center">
                                @if($d->getLastTrainingData())
                                <a href="{{route('bls_viewbatch', $d->getLastTrainingData()->batch->id)}}">{{$d->getLastTrainingData()->batch->batch_name}}</a>
                                @else
                                <div>N/A</div>
                                @endif
                            </td>
                            <td class="text-center">{{($d->getLastTrainingData()) ? Carbon\Carbon::parse($d->getLastTrainingData()->batch->training_date_start)->format('m/d/Y') : 'N/A'}}</td>
                            <td class="text-center">{{($d->getLastTrainingData()) ? Carbon\Carbon::parse($d->getLastTrainingData()->batch->training_date_start)->format('Y') : 'N/A'}}</td>
                            <td class="text-center">{{$d->ifForRefresher()}}</td>
                            <td class="text-center"></td>
                        </tr>
                        @else

                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#mainTbl').DataTable({
            dom: 'QBfritp',
            buttons: [
                {
                    extend: 'excel',
                    title: '',
                },
                'copy',
            ],
        });
    });
</script>
@endsection