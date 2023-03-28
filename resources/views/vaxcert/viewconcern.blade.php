@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('vaxcert_processpatient', $d->id)}}" method="POST">
        <div class="card">
            <div class="card-header">View</div>
            <div class="card-body">
                @php
                $step = 1;
                @endphp
                <ul>
                    @if(!is_null($d->vaxcert_refno))
                    <li>Step {{$step}}
                        <ul>
                            <li>asdasd</li>
                        </ul>
                    </li>
                    @php
                    $step++;
                    @endphp
                    
                    @else
                    <li>Step {{$step}}
                        <ul>
                            <li>Login to VASLinelist Website first then Search for Patient details by clicking <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$d->last_name}}&firstname={{$d->first_name}}&birthdate={{date('Y-m-d', strtotime($d->bdate))}}{{(!is_null($d->suffix)) ? '&suffix='.$d->suffix : ''}}" target="_blank">HERE</a></li>
                            <ul>
                                <li>If FOUND, Double check details if complete.</li>
                            </ul>
                        </ul>
                    </li>
                    @endif
                    
                </ul>
                <a href="{{route('vaxcert_basedl', $d->id)}}" class="btn btn-primary btn-block">Download Base Template</a>
                <a href="{{route('vaxcert_offdl', $d->id)}}" class="btn btn-primary btn-block">Download Offline Template</a>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="update">Update Record</button>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-danger mr-3" name="submit" value="update">Reject</button>
                <button type="submit" class="btn btn-success" name="submit" value="update">Complete</button>
            </div>
        </div>
    </form>
</div>
@endsection