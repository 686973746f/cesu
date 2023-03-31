@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('vaxcert_processpatient', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">View</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="concern_msg"><span class="text-danger font-weight-bold">*</span>Specific Concern Message</label>
                    <textarea class="form-control" name="concern_msg" id="concern_msg" rows="3" placeholder="Ipaliwanag dito ang isyu na nais ipa-resolba saamin. (Halimbawa: Hindi nalabas ang aking First Dose, Mali ang spelling ng pangalan ko, Mali ang Birthday ko, atbp.)">{{$v->concern_msg}}</textarea>
                </div>
                <hr>
                <ul>
                    <li><b>Step 1:</b> Verify <a href="">Submitted ID</a> and <a href="">Vaccination Card</a> of the patient.</li>
                    <li><b>Step 2:</b> Login to VAS Line List system first.</li>
                    <li>
                        <b>Step 3:</b>
                        <ul>
                            @if(!is_null($d->vaxcert_refno))
                            <li>Search Ref. No in <b>Correction Request</b> - <a href="https://vaslinelist.dict.gov.ph/vaxcert/correction?lastname={{$v->vaxcert_refno}}">HERE</a></li>
                            <li>Search Ref. No in <b>Not Found Request</b> - <a href="https://vaslinelist.dict.gov.ph/vaxcert/not-found?lastname={{$v->vaxcert_refno}}">HERE</a></li>
                            @endif
                            <li>Search Name of Patient in <b>Correction Request</b> by clicking - <a href="https://vaslinelist.dict.gov.ph/vaxcert/correction?lastname={{$d->last_name}}&firstname={{$d->first_name}}" target="_blank">HERE</a></li>
                            <li>Search Name of Patient in <b>Not Found Request</b> by clicking - <a href="https://vaslinelist.dict.gov.ph/vaxcert/not-found?lastname={{$d->last_name}}&firstname={{$d->first_name}}" target="_blank">HERE</a></li>
                        </ul>
                    </li>
                    <h6>(Kung may ticket ang Patient, wag na mag-proceed sa Step 3 at i-update na lang ang Ticket at i-close pagkatapos. Kung wala, proceed to Step 3)</h6>
                    <h6>------------</h6>
                    <li>
                        <b>Step 4:</b>
                        <ul>
                            <li>Search and check record of patient in Vacinee Query by clicking - <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$d->last_name}}&firstname={{$d->first_name}}&birthdate={{date('Y-m-d', strtotime($d->bdate))}}{{(!is_null($d->suffix)) ? '&suffix='.$d->suffix : ''}}" target="_blank">HERE</a></li>
                            <h6>(Kung may lumabas, i-check at i-update ang mga details)</h6>
                            @if(date('d') <= 12)
                            <ul>
                                <li>IF NOT FOUND, It is possible that the Birthdate of Patient was reversed, you can check it by clicking - <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$d->last_name}}&firstname={{$d->first_name}}&birthdate={{date('Y-d-m', strtotime($d->bdate))}}{{(!is_null($d->suffix)) ? '&suffix='.$d->suffix : ''}}" target="_blank">HERE</a></li>
                                <h6>(Kung may lumabas, itama ang birthdate ng patient at i-submit para ma-update)</h6>
                            </ul>
                            @endif
                            <h6>(Kung kumpleto na ang bakuna after updating, wag na mag-proceed sa Step 4 at pindutin na ang Complete button sa ibaba ng page na ito)</h6>
                        </ul>
                    </li>
                    <h6>------------</h6>
                    <li>
                        <b>Step 5:</b>
                        <ul>
                            <li>Download Patient Linelist Template by clicking - <a href="{{route('vaxcert_basedl', $d->id)}}">HERE</a></li>
                            <li>Go to <a href="https://vaslinelist.dict.gov.ph/vas-line-import/approved">VAS Linelist Import</a> and upload the downloaded Excel (.XLSX) file.</li>
                            <li>Use <b class="text-info">cesugentri.vaxcert@gmail.com</b> as the email for uploading the linelist.</li>
                        </ul>
                    </li>
                </ul>
                <!--
                    <a href="{{route('vaxcert_offdl', $d->id)}}" class="btn btn-primary btn-block">Download Offline Template</a>
                    <button type="submit" class="btn btn-primary btn-block" name="submit" value="update">Update Record</button>
                -->
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-danger mr-3" name="submit" value="reject">Reject</button>
                <button type="submit" class="btn btn-success" name="submit" value="complete">Complete</button>
            </div>
        </div>
    </form>
</div>
@endsection