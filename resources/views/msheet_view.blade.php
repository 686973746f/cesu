@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif;">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">COVID-19 Online Contact Tracing Sign and Symptom Log Form</div>
                    <div><a href="{{route('msheet.print', ['id' => $data->id])}}" class="btn btn-primary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</a></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                    <label for="">Patient Name / ID</label>
                    <input type="text" class="form-control" value="{{$data->forms->records->getName()}} (#{{$data->forms->records->id}})" readonly>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Confirmed Case ID</label>
                            <input type="text" class="form-control" value="{{$data->id}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="text" class="form-control" value="{{$data->created_at->format('m/d/Y')}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Region</label>
                            <input type="text" class="form-control" value="{{$data->region}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="">Close Contact Name</label>
                  <input type="text" class="form-control" value="{{$data->ccname}}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Date of Last Exposure</label>
                            <input type="text" class="form-control" value="{{$data->date_lastexposure}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Date of Voluntary Quarantine Period Ends</label>
                            <input type="text" class="form-control" value="{{date('m/d/Y', strtotime($data->date_endquarantine))}}" readonly>
                            <small class="text-muted">Date based on Date Interviewed.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                  <label for="">Share to Patient URL</label>
                  <input type="text" class="form-control" value="{{route('msheet.guest.view', ['magicurl' => $data->magicURL])}}" readonly>
                </div>
                <div class="alert alert-info" role="alert">
                    <strong><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>INSTRUCTIONS</strong>
                    <hr>
                    Monitoring shall be done twice a day. Indicate the date. Go through each condition for monitoring. Put a check if the close contact met the condition being asked under the corresponding time of the day (AM/PM) monitoring was done. Provide the temperature taken <i>(e.g., 38.3)</i>.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2">Conditions for Monitoring</th>
                                @foreach($period as $date)
                                <th colspan="2">{{$date->format('m/d/Y (D)')}}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($period as $date)
                                <th><a href="{{route('msheet.viewdate', ['id' => $data->id, 'date' => $date->format('Y-m-d'), 'mer' => 'AM'])}}">AM</a></th>
                                @if($date->format('Y-m-d') == date('Y-m-d'))
                                @if($currentmer == 'AM')
                                <th>PM</th>
                                @else
                                <th><a href="{{route('msheet.viewdate', ['id' => $data->id, 'date' => $date->format('Y-m-d'), 'mer' => 'PM'])}}">PM</a></th>
                                @endif
                                @else
                                <th><a href="{{route('msheet.viewdate', ['id' => $data->id, 'date' => $date->format('Y-m-d'), 'mer' => 'PM'])}}">PM</a></th>
                                @endif
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
                <p><i>*Quarantine Period Ends 14 days after Date of Last Exposure</i></p>
            </div>
        </div>
    </div>
@endsection