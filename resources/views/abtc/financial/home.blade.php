@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>For Uploading</b></div>
                    <div>
                        @if(request()->input('showSubmittedClaims'))
                        <a href="{{route('abtc_financial_home')}}" class="btn btn-primary">View For Uploading</a>
                        @else
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateReportModal">Generate Report</button>

                        <a href="{{route('abtc_financial_home')}}?showSubmittedClaims=1" class="btn btn-success">View Submitted Claims</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>Record ID</th>
                            <th>Name</th>
                            <th>Facility</th>
                            <th>Date Admitted</th>
                            <th>Date Discharged</th>
                            <th>Transmittal Days</th>
                            @if(request()->input('showSubmittedClaims'))
                            <th>Claim Status</th>
                            @endif
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">#{{$d->id}}</td>
                            <td>{{$d->patient->getName()}}</td>
                            <td class="text-center">{{$d->vaccinationsite->site_name}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($d->d0_date))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($d->d7_date))}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($d->d7_date)->diffInDays()}}</td>
                            @if(request()->input('showSubmittedClaims'))
                            <td class="text-center">{{$d->ics_claims_status}}</td>
                            @endif
                            <td class="text-center">
                                @if(request()->input('showSubmittedClaims'))
                                <a href="{{route('abtc_financial_viewticket', $d->id)}}">View Ticket</a>
                                @else
                                <a href="{{route('abtc_financial_claimticket', ['ticket_id' => $d->id])}}">Select Ticket</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <form action="{{ route('abtc_financial_generate_report') }}" method="POST">
        @csrf
        <div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><b class="text-danger">*</b>Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" min="2025-01-01" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><b class="text-danger">*</b>End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" min="2025-01-01" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block" name="submit" value="IBNR">IBNR</button>
                        <button type="submit" class="btn btn-success btn-block" name="submit" value="CERTIFICATION">Certification</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#mainTbl').dataTable({
            iDisplayLength: -1,
            fixedHeader: true,
            dom: 'bftrip',
        });
    </script>
@endsection