@extends('layouts.app_pdf')
<style>
    @page { margin: 0; }
    body { margin: 0; }
</style>
@section('content')
<div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif;page-break-after: avoid;">
    <div class="text-right">
        <h4 class="text-white font-weight-bold mx-3"><span style="background-color: black">COVID-19 Contact Tracing Sign and Symptom Log Form</span></h4>
    </div>
    <p class="mx-5"><strong>Name:</strong> <u>{{$data->forms->records->getName()}}</u></p>
    <div class="row">
        <div class="col-md-4">
            <p class="mx-5"><strong>Confirmed Case ID:</strong> ____________________________________________</p>
        </div>
        <div class="col-md-4">
            <p class="mx-5"><strong>Date:</strong> <u>{{date('m/d/Y', strtotime($data->created_at))}}</u></p>
        </div>
        <div class="col-md-4">
            <p class="mx-5"><strong>Region:</strong> <u>{{$data->region}}</u></p>
        </div>
    </div>
    <p class="mx-5"><strong>Close Contact Name:</strong> __________________________________________________________________________________________________________________________________________________________</p>
    <div class="row">
        <div class="col-md-4">
            <p class="mx-5"><strong>Date of Last Exposure:</strong> <u>{{date('m/d/Y', strtotime($data->date_lastexposure))}}</u></p>
        </div>
        <div class="col-md-4">
            <p class="mx-5"><strong>Date of Voluntary Quarantine Period Ends*:</strong> <u>{{date('m/d/Y', strtotime($data->date_endquarantine))}}</u></p>
        </div>
    </div>
    <p class="mx-5"><strong>INSTRUCTIONS:</strong> Monitoring shall be done twice a day. Indicate the date. Go through each condition for monitoring. Put a check if the close contact met the condition being asked under the corresponding time of the day (AM/PM) monitoring was done. Provide the temperature taken (e.g., 38.3).</p>
    <div class="table-responsive">
        <table class="table table-bordered text-center" style="font-size: 60%">
            <thead>
                <tr>
                    <th rowspan="2">Conditions for Monitoring</th>
                    @foreach($period as $date)
                    <th colspan="2">{{$date->format('m/d/Y')}}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($period as $date)
                    <th class="font-weight-normal">AM</th>
                    <th class="font-weight-normal">PM</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row">No Sign/Symptom</td>
                    @foreach($period as $date)
                    <td>{{($data->ifnosx($date->format('Y-m-d'), 'AM')) ? '✔' : ''}}</td>
                    <td>{{($data->ifnosx($date->format('Y-m-d'), 'PM')) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Fever</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->WhereNotNull('fever')->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->WhereNotNull('fever')->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Cough</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('cough', 1)->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('cough', 1)->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Sore Throat</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('sorethroat', 1)->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('sorethroat', 1)->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Difficulty of Breathing</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('dob', 1)->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('dob', 1)->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Colds</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('colds', 1)->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('colds', 1)->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Diarrhea</td>
                    @foreach($period as $date)
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('diarrhea', 1)->first()) ? '✔' : ''}}</td>
                    <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('diarrhea', 1)->first()) ? '✔' : ''}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td scope="row">Other Symptoms</td>
                    @foreach($period as $date)
                    <td>{{(!is_null($data->getos($date->format('Y-m-d'), 'AM'))) ? $data->getos($date->format('Y-m-d'), 'AM') : ''}}</td>
                    <td>{{(!is_null($data->getos($date->format('Y-m-d'), 'AM'))) ? $data->getos($date->format('Y-m-d'), 'PM') : ''}}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <p class="mx-5"><i>*Quarantine Period Ends 14 days after Date of Last Exposure</i></p>
</div>
@endsection