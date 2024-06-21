@extends('layouts.app')

@section('content')
<style>
    #ntable td {
        vertical-align: middle;
    }

    #fftable td {
        vertical-align: middle;
    }
</style>
<div class="container">
    @if(session('msg'))
    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
        {{session('msg')}}
    </div>
    @endif
    <div class="row mb-3">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#nvm"><i class="fas fa-search mr-2"></i>Search Existing Patient</button>
        </div>
        <div class="col-md-6">
            <a href="{{route('abtc_patient_create')}}" class="btn btn-success btn-block"><i class="fas fa-user-plus mr-2"></i>Add New Patient</a>
        </div>
    </div>
    <form action="{{route('abtc_qr_quicksearch')}}" method="POST" autocomplete="off">
        @csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Quick Search via QR Code / Registration No." name="qr" id="qr" required autofocus>
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-qrcode mr-2"></i><i class="fas fa-hashtag mr-2"></i>Quick Search</button>
            </div>
        </div>
    </form>
    <hr>
    <form action="" method="GET">
        <div class="input-group mb-3">
            <input type="date" class="form-control" name="d" id="d" value="{{(request()->input('d')) ? request()->input('d') : date('Y-m-d')}}" required>
            <div class="input-group-append">
                <button class="btn btn-outline-success" type="submit"><i class="fas fa-calendar-alt mr-2"></i>Date Search</button>
            </div>
        </div>
    </form>
    <!--
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b><span class="text-success">New Patients</span> for {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div>Total: {{$new->count()}}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ntable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Registration #</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Age/Gender</th>
                            <th>Brgy</th>
                            <th>Animal</th>
                            <th>Exposure Date</th>
                            <th>Category</th>
                            <th>Body Part</th>
                            <th>Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($new as $n)
                        <tr>
                            <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                            <td><a href="{{route('abtc_patient_edit', [$n->patient->id])}}">{{$n->patient->getName()}}</a></td>
                            <td class="text-center">{{(!is_null($n->patient->contact_number)) ? $n->patient->contact_number : 'N/A'}}</td>
                            <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                            <td class="text-center"><small>{{$n->patient->address_brgy_text}}</small></td>
                            <td class="text-center">{{$n->animal_type}}</td>
                            <td class="text-center">{{date('m/d/Y (D)', strtotime($n->bite_date))}}</td>
                            <td class="text-center">{{$n->category_level}}</td>
                            <td class="text-center">{{(!is_null($n->body_site)) ? mb_strtoupper($n->body_site) : 'N/A'}}</td>
                            <td class="text-center"><small>{{date('m/d/Y H:i:s', strtotime($n->created_at))}}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    -->
    <div class="card" id="ffupCard">
        <div class="card-header">
            <div class="row">
                <div class="col-4"><b><span class="text-primary">Follow-up Patients</span> {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div class="col-8">
                    <h4 style="display:inline-block;"><span class="badge badge-secondary">Total: {{$ff_total->count()}}</span></h4>
                    <h4 style="display:inline-block;">
                        <span class="badge badge-success">Completed: {{$completed_d3 + $completed_d7}}/{{$ff_total->count()}}</span>
                    </h4>
                    <h4 style="display:inline-block;">
                        <span class="badge" style="background-color: orange;">Pending: <span id="put_pendingtotal"></span> (New: <span id="put_pendingnew"></span> - Booster: <span id="put_pendingbooster"></span>)</span>
                    </h4>
                    <div class="row">
                        <div class="col-6">
                            <div>Done D0 = {{$completed_d0_total}} (New: {{$completed_d0_total - $completed_d0_otherarea}} | Other Area: {{$completed_d0_otherarea}})</div>
                            <div>Done D3 = {{$completed_d3}}</div>
                            <div>Done D7 = {{$completed_d7}}</div>
                        </div>
                        <div class="col-6">
                            <div>Pending D3 = <span id="putd3here"></span></div>
                            <div>Pending D7 = <span id="putd7here"></span></div>
                            <div class="mb-3">Possible D28 (Not counted in Pending) = {{$possible_d28_count}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                    
                </div>
            </div>
            <div class="text-right">
                <a href="{{route('abtc_ffsms')}}?d={{$sdate}}">Create SMS Format to Pending List</a>
            </div>
        </div>
        <div class="card-body">
            @php
            $d3_total = 0;
            $d7_total = 0;

            $total_pending = 0;
            $pending_new = 0;
            $pending_booster = 0;
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="fftable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th></th>
                            <th>Registration #</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Age/Gender</th>
                            <th>Brgy</th>
                            <th>Animal</th>
                            <th>Exposure Date</th>
                            <th>Category</th>
                            <th>Body Part</th>
                            <th>Day</th>
                            <th>Type</th>
                            <th>Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ff_row as $n)
                        <tr>
                            <td class="text-center">
                                @if(!is_null($n->getCurrentDose()))
                                    @if($n->ifCanProcessQuickMark() == 'Y')
                                        @if($n->ifPatientLastDoseNormal())
                                        <!-- Ask if the Animal is Alive, Died or Missing -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#askAnimal">Mark as Done</button>
                                        @else
                                        <a href="{{route('abtc_encode_process', ['br_id' => $n->id, 'dose' => $n->getCurrentDose()])}}?fsc=1" class="btn btn-primary btn-sm" onclick="return confirm('Confirm process. Patient {{$n->patient->getName()}} (#{{$n->case_id}}) should be present. Click OK to proceed.')">Mark as Done</a>
                                        @endif
                                    @else
                                        @php
                                            $presentDate = Carbon\Carbon::now();
                                            $now = Carbon\Carbon::parse($sdate);
                                            $date_check = Carbon\Carbon::parse($n->getCurrentDoseDate());
                                        @endphp
                                        @if($presentDate->lt($date_check))
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$n->ifCanProcessQuickMark()}}">
                                            <button class="btn btn-primary btn-sm" style="pointer-events: none;" type="button" disabled>Mark as Done</button>
                                        </span>
                                        @else
                                            @if($date_check->diffInDays($presentDate) < 3)
                                            <a href="{{route('abtc_encode_process_late', ['br_id' => $n->id, 'dose' => $n->getCurrentDose()])}}?fsc=1" class="btn btn-primary btn-sm" onclick="return confirm('Confirm process. Patient {{$n->patient->getName()}} (#{{$n->case_id}}) should be present. Click OK to proceed.')">Proceed LATE Vaccination</a>
                                            @else
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$n->ifCanProcessQuickMark()}}">
                                                <button class="btn btn-primary btn-sm" style="pointer-events: none;" type="button" disabled>Mark as Done</button>
                                            </span>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                            
                            <td><a href="{{route('abtc_patient_edit', [$n->patient->id])}}">{{$n->patient->getName()}}</a></td>
                            <td class="text-center">{{(!is_null($n->patient->contact_number)) ? $n->patient->contact_number : 'N/A'}}</td>
                            <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                            <td class="text-center"><small>{{$n->patient->address_brgy_text}}</small></td>
                            <td class="text-center">{{$n->animal_type}}</td>
                            <td class="text-center">{{date('m/d/Y (D)', strtotime($n->bite_date))}}</td>
                            <td class="text-center">{{$n->category_level}}</td>
                            <td class="text-center">{{(!is_null($n->body_site)) ? mb_strtoupper($n->body_site) : 'N/A'}}</td>
                            <td class="text-center">{{$n->getlatestday()}}</td>
                            <td class="text-center">{{$n->getType()}}</td>
                            <td class="text-center"><small>{{date('m/d/Y H:i:s', strtotime($n->created_at))}}</small></td>
                        </tr>
                        @php
                        $total_pending++;

                        if($n->getlatestday() == 'D3') {
                            $d3_total++;   
                        }
                        else if($n->getlatestday() == 'D7') {
                            $d7_total++;
                        }

                        if($n->getType() == 'NEW PATIENT') {
                            $pending_new++;
                        }
                        else {
                            $pending_booster++;
                        }
                        
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-none">
            D3 - <span id="d3_text">{{$d3_total}}</span> | D7 - <span id="d7_text">{{$d7_total}}</span>
            Pending New - <span id="pending_new">{{$pending_new}}</span> | Pending Booster - <span id="pending_booster">{{$pending_booster}}</span>
            Pending Total - <span id="total_pending">{{$total_pending}}</span>
        </div>
    </div>
</div>

<form action="{{route('abtc_search_init')}}" method="POST">
    @csrf
    <div class="modal fade" id="nvm" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">New Vaccination</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="patient_id" class="form-label">Select Patient to Encode</label>
                <select class="form-select" name="patient_id" id="patient_id" onchange="this.form.submit()" required>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</button>
            </div>
          </div>
        </div>
    </div>
</form>

<form action="" method="GET">
    <div class="modal fade" id="askAnimal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Animal Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="biting_animal_status">Biting Animal Status (After 14 Days)</label>
                      <select class="form-control" name="biting_animal_status" id="biting_animal_status" required>
                        <option value="N/A" {{(old('biting_animal_status') == 'N/A') ? 'selected' : ''}}>N/A</option>
                        <option value="ALIVE" {{(old('biting_animal_status') == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                        <option value="DEAD" {{(old('biting_animal_status') == 'DEAD') ? 'selected' : ''}}>Dead</option>
                        <option value="LOST" {{(old('biting_animal_status') == 'LOST') ? 'selected' : ''}}>Lost/Unknown</option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Mark as Done</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#putd3here').text($('#d3_text').text());
    $('#putd7here').text($('#d7_text').text());

    $('#put_pendingtotal').text($('#total_pending').text());
    $('#put_pendingnew').text($('#pending_new').text());
    $('#put_pendingbooster').text($('#pending_booster').text());

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('#ntable').DataTable({
        fixedHeader: true,
        dom: 'frti',
        iDisplayLength: -1,
    });

    $('#fftable').DataTable({
        fixedHeader: true,
        dom: 'frti',
        iDisplayLength: -1,
        order: [[1, 'asc']],
    });

    $(document).ready(function () {
        $('#patient_id').select2({
            dropdownParent: $("#nvm"),
            theme: "bootstrap",
            placeholder: 'Search by SURNAME, FIRST NAME or Patient ID ...',
            ajax: {
                url: "{{route('abtc_patient_ajaxlist')}}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id,
                                class: item.class,
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection