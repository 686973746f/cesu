@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif;">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">COVID-19 Online Contact Tracing Sign and Symptom Log Form</div>
                    <div><button type="button" class="btn btn-primary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
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
                                <th colspan="2">{{$date->format('m/d/Y')}}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($period as $date)
                                <th><button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#dd{{$date->format('mdY')}}_AM">AM</button></th>
                                <th><button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#dd{{$date->format('mdY')}}_PM">PM</button></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">Fever</td>
                                @foreach($period as $date)
                                <td></td>
                                <td></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td scope="row">Cough</td>
                                @foreach($period as $date)
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('cough', 1)->first()) ? 'O' : ''}}</td>
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('cough', 1)->first()) ? 'O' : ''}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td scope="row">Sore Throat</td>
                                @foreach($period as $date)
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('sorethroat', 1)->first()) ? 'O' : ''}}</td>
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('sorethroat', 1)->first()) ? 'O' : ''}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td scope="row">Difficulty of Breathing</td>
                                @foreach($period as $date)
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('dob', 1)->first()) ? 'O' : ''}}</td>
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('dob', 1)->first()) ? 'O' : ''}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td scope="row">Colds</td>
                                @foreach($period as $date)
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('colds', 1)->first()) ? 'O' : ''}}</td>
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('colds', 1)->first()) ? 'O' : ''}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td scope="row">Diarrhea</td>
                                @foreach($period as $date)
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'AM')->where('diarrhea', 1)->first()) ? 'O' : ''}}</td>
                                <td>{{($subdata->where('forDate', $date->format('Y-m-d'))->where('forMeridian', 'PM')->where('diarrhea', 1)->first()) ? 'O' : ''}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p><i>*Quarantine Period Ends 14 days after Date of Last Exposure</i></p>
            </div>
        </div>
    </div>

    @foreach($period as $date)
    <form action="{{route('msheet.updatemonitoring', ['id' => $data->id, 'date' => $date->format('Y-m-d'), 'mer' => 'AM'])}}" method="POST">
        @csrf
        <div class="modal fade" id="dd{{$date->format('mdY')}}_AM" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{$date->format('m/d/Y')}} - <strong>AM</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="fever" id="fever" value="1">
                            Fever
                          </label>
                        </div>
                        <div class="form-group mt-3">
                          <label for="fevertemp">Temperature (in Celcius)</label>
                          <input type="number" class="form-control" name="fevertemp" id="fevertemp" step=".1" min="37.5" max="50">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="cough" id="cough" value="1">
                                Cough
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="sorethroat" id="sorethroat" value="1">
                                Sore Throat
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="dob" id="dob" value="1">
                                Difficulty of Breathing
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="colds" id="colds" value="1">
                                Colds
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="diarrhea" id="diarrhea" value="1">
                                Diarrhea
                            </label>
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #1</label>
                            <input type="text" class="form-control" name="os1" id="os1">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #2</label>
                            <input type="text" class="form-control" name="os2" id="os2">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #3</label>
                            <input type="text" class="form-control" name="os3" id="os3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="{{route('msheet.updatemonitoring', ['id' => $data->id, 'date' => $date->format('Y-m-d'), 'mer' => 'PM'])}}" method="POST">
        @csrf
        <div class="modal fade" id="dd{{$date->format('mdY')}}_PM" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{$date->format('m/d/Y')}} - <strong>PM</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="fever" id="fever" value="1">
                            Fever
                          </label>
                        </div>
                        <div class="form-group mt-3">
                          <label for="fevertemp">Temperature (in Celcius)</label>
                          <input type="number" class="form-control" name="fevertemp" id="fevertemp" step=".1" min="37.5" max="50">
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="cough" id="cough" value="1">
                                Cough
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="sorethroat" id="sorethroat" value="1">
                                Sore Throat
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="dob" id="dob" value="1">
                                Difficulty of Breathing
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="colds" id="colds" value="1">
                                Colds
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="diarrhea" id="diarrhea" value="1">
                                Diarrhea
                            </label>
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #1</label>
                            <input type="text" class="form-control" name="os1" id="os1">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #2</label>
                            <input type="text" class="form-control" name="os2" id="os2">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #3</label>
                            <input type="text" class="form-control" name="os3" id="os3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endforeach
@endsection