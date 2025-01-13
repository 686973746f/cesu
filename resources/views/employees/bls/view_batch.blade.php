@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <div><b>{{$d->batch_name}}</b></div>
                    <div><a href="{{route('bls_home_batches')}}">Go Back</a></div>
                </div>
                <div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#joinParticipant">Join a Participant</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParticipant">Create and Join Participant</button>
                    <a href="{{route('bls_download_db', $d->id)}}" class="btn btn-primary">Download Database</a>
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
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="5" style="vertical-align: middle;">No.</th>
                            <th rowspan="5" style="vertical-align: middle;">Name</th>
                            <th rowspan="5" style="vertical-align: middle;">Type of Provider</th>
                            <th rowspan="5" style="vertical-align: middle;">Position</th>
                            <th rowspan="5" style="vertical-align: middle;">Institution/Agency</th>
                            <th rowspan="5" style="vertical-align: middle;">Status of Employment</th>
                            <th rowspan="5" style="vertical-align: middle;">Date of Birth</th>
                            <th rowspan="5" style="vertical-align: middle;">Address</th>
                            <th rowspan="5" style="vertical-align: middle;">Contact Details</th>
                            <th rowspan="5" style="vertical-align: middle;">Email Address</th>
                            <th rowspan="5" style="vertical-align: middle;">Code Name</th>
                            <th colspan="4" rowspan="2" style="vertical-align: middle;">SFA</th>
                            <th colspan="10" style="vertical-align: middle;">BLS</th>
                            <th rowspan="5" style="vertical-align: middle;">Pass/Fail</th>
                            <th rowspan="5" style="vertical-align: middle;">Affective (10%)</th>
                            <th rowspan="5" style="vertical-align: middle;">Final Remarks</th>
                            <th rowspan="5" style="vertical-align: middle;">BLS ID</th>
                            <th rowspan="5" style="vertical-align: middle;">Expiration Date</th>
                            <th rowspan="5" style="vertical-align: middle;">Picture</th>
                        </tr>
                        <tr>
                            <th colspan="4" style="vertical-align: middle;">COGNITIVE (30%)</th>
                            <th colspan="7" style="vertical-align: middle;">PSYCHOMOTOR (60%)</th>
                        </tr>
                        <tr>
                            <th rowspan="3" style="vertical-align: middle;">Pre-Test</th>
                            <th rowspan="3" style="vertical-align: middle;">Post Test</th>
                            <th rowspan="3" style="vertical-align: middle;">Remedial</th>
                            <th rowspan="3" style="vertical-align: middle;">Pass/Fail</th>
                            <th rowspan="3" style="vertical-align: middle;">Pre-Test</th>
                            <th rowspan="3" style="vertical-align: middle;">Post Test</th>
                            <th rowspan="3" style="vertical-align: middle;">Remedial</th>
                            <th rowspan="3" style="vertical-align: middle;">Pass/Fail</th>
                            
                        </tr>
                        <tr>
                            <th colspan="2" style="vertical-align: middle;">CPR w/ AED</th>
                            <th colspan="2" style="vertical-align: middle;">FBAO</th>
                            <th colspan="2" style="vertical-align: middle;">RB</th>
                        </tr>
                        <tr>
                            <th>ADULT</th>
                            <th>INFANT</th>
                            <th>ADULT</th>
                            <th>INFANT</th>
                            <th>ADULT</th>
                            <th>INFANT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member_list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td><a href="{{route('bls_viewparticipant', $l->id)}}"><b>{{$l->member->getName()}}</b></a></td>
                            <td class="text-center">{{$l->member->provider_type}}</td>
                            <td class="text-center">{{$l->member->position}}</td>
                            <td class="text-center">{{$l->member->institution}}</td>
                            <td class="text-center">{{$l->member->employee_type}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($l->member->bdate)->format('m/d/Y')}}</td>
                            <td class="text-center">{{$l->member->getAddress()}}</td>
                            <td class="text-center">{{$l->member->contact_number}}</td>
                            <td class="text-center">{{$l->member->email}}</td>
                            <td class="text-center">{{$l->member->codename}}</td>
                            <td class="text-center">{{$l->sfa_pretest}}</td>
                            <td class="text-center">{{$l->sfa_posttest}}</td>
                            <td class="text-center">{{$l->sfa_remedial}}</td>
                            <td class="text-center">{{$l->sfa_ispassed}}</td>
                            <td class="text-center">{{$l->bls_pretest}}</td>
                            <td class="text-center">{{$l->bls_posttest}}</td>
                            <td class="text-center">{{$l->bls_remedial}}</td>
                            <td class="text-center">{{$l->bls_cognitive_ispassed}}</td>
                            <td class="text-center">{{$l->bls_cpr_adult}}</td>
                            <td class="text-center">{{$l->bls_cpr_infant}}</td>
                            <td class="text-center">{{$l->bls_fbao_adult}}</td>
                            <td class="text-center">{{$l->bls_fbao_infant}}</td>
                            <td class="text-center">{{$l->bls_rb_adult}}</td>
                            <td class="text-center">{{$l->bls_rb_infant}}</td>
                            <td class="text-center">{{$l->bls_psychomotor_ispassed}}</td>
                            <td class="text-center">{{$l->bls_affective}}</td>
                            <td class="text-center">{{$l->bls_finalremarks}}</td>
                            <td class="text-center">{{$l->bls_id_number}}</td>
                            <td class="text-center">{{($l->bls_expiration_date) ? Carbon\Carbon::parse($l->bls_expiration_date) : 'N/A'}}</td>
                            <td class="text-center">
                                @if($l->picture)
                                <img src="{{asset('assets/bls/members/'.$l->picture)}}" class="img-fluid">
                                @else
                                <div>N/A</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{route('bls_joinparticipant', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="joinParticipant" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Join a Participant to this Batch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="member_parent">
                      <label for="member_id"><b class="text-danger">*</b>Select Participant to Join</label>
                      <select class="form-control" name="member_id" id="member_id" required>
                        <option value="" disabled {{(is_null(old('member_id')) ? 'selected' : '')}}>Choose...</option>
                        @foreach($possible_participants_list as $p)
                        <option value="{{$p->id}}">{{$p->getName()}} (ID: {{$p->id}})</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('employees.bls.store_member_modal')

<script>
    $('#member_id').select2({
        theme: 'bootstrap',
        dropdownParent: $('#member_parent'),
        placeholder: 'Search by Name or Member ID...',
    });
</script>
@endsection