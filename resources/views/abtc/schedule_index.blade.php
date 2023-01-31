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
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b><span class="text-primary">Follow-up Patients</span> {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div>Total: {{$ff->count()}}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="fftable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th></th>
                            <th>Registration #</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Brgy</th>
                            <th>Animal</th>
                            <th>Exposure Date</th>
                            <th>Category</th>
                            <th>Body Part</th>
                            <th>Day</th>
                            <th>Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ff as $n)
                        <tr>
                            <td class="text-center">
                                @if(!is_null($n->getCurrentDose()))
                                    @if($n->ifCanProcessQuickMark() == 'Y')
                                    <a href="{{route('abtc_encode_process', ['br_id' => $n->id, 'dose' => $n->getCurrentDose()])}}?fsc=1" class="btn btn-primary btn-sm" onclick="return confirm('Confirm process. Patient {{$n->patient->getName()}} (#{{$n->case_id}}) should be present. Click OK to proceed.')">Mark as Done</a>
                                    @else
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$n->ifCanProcessQuickMark()}}">
                                        <button class="btn btn-primary btn-sm" style="pointer-events: none;" type="button" disabled>Mark as Done</button>
                                    </span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                            <td><a href="{{route('abtc_patient_edit', [$n->patient->id])}}">{{$n->patient->getName()}}</a></td>
                            <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                            <td class="text-center"><small>{{$n->patient->address_brgy_text}}</small></td>
                            <td class="text-center">{{$n->animal_type}}</td>
                            <td class="text-center">{{date('m/d/Y (D)', strtotime($n->bite_date))}}</td>
                            <td class="text-center">{{$n->category_level}}</td>
                            <td class="text-center">{{(!is_null($n->body_site)) ? mb_strtoupper($n->body_site) : 'N/A'}}</td>
                            <td class="text-center">{{$n->getlatestday()}}</td>
                            <td class="text-center"><small>{{date('m/d/Y H:i:s', strtotime($n->created_at))}}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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

<script>
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