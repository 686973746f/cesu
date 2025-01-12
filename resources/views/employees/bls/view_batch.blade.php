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
                        @foreach($member_list as $ind => $d)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td><a href="{{route('bls_viewmember', $d->id)}}"><b>{{$d->getName()}}</b></a></td>
                            <td class="text-center">{{$d->provider_type}}</td>
                            <td class="text-center">{{$d->position}}</td>
                            <td class="text-center">{{$d->institution}}</td>
                            <td class="text-center">{{$d->employee_type}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($d->bdate)->format('m/d/Y')}}</td>
                            <td class="text-center">{{$d->getAddress()}}</td>
                            <td class="text-center">{{$d->contact_number}}</td>
                            <td class="text-center">{{$d->email}}</td>
                            <td class="text-center">{{$d->codename}}</td>
                            <td class="text-center">{{$d->sfa_pretest}}</td>
                            <td class="text-center">{{$d->sfa_posttest}}</td>
                            <td class="text-center">{{$d->sfa_remedial}}</td>
                            <td class="text-center">{{$d->sfa_ispassed}}</td>
                            <td class="text-center">{{$d->bls_pretest}}</td>
                            <td class="text-center">{{$d->bls_posttest}}</td>
                            <td class="text-center">{{$d->bls_remedial}}</td>
                            <td class="text-center">{{$d->bls_cognitive_ispassed}}</td>
                            <td class="text-center">{{$d->bls_cpr_adult}}</td>
                            <td class="text-center">{{$d->bls_cpr_infant}}</td>
                            <td class="text-center">{{$d->bls_fbao_adult}}</td>
                            <td class="text-center">{{$d->bls_fbao_infant}}</td>
                            <td class="text-center">{{$d->bls_rb_adult}}</td>
                            <td class="text-center">{{$d->bls_rb_infant}}</td>
                            <td class="text-center">{{$d->bls_psychomotor_ispassed}}</td>
                            <td class="text-center">{{$d->bls_affective}}</td>
                            <td class="text-center">{{$d->bls_finalremarks}}</td>
                            <td class="text-center">{{$d->bls_id_number}}</td>
                            <td class="text-center">{{($d->bls_expiration_date) ? Carbon\Carbon::parse($d->bls_expiration_date) : 'N/A'}}</td>
                            <td class="text-center">
                                <img src="{{asset('assets/bls/members/'.$d->picture)}}" class="img-fluid">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection