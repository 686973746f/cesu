@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <div>Welcome, {{auth()->user()->name}}</div>
            <div>Date: {{date('m/d/Y (D)')}} | Morbidity Week: {{date('W')}}</div>
          </div>
          <hr>
          <div class="d-flex justify-content-between">
            <div><b>ABTC Menu</b></div>
            <div>
              @if(auth()->user()->canaccess_covid == 1)
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changemenu">Change</button>
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
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#qs"><i class="fas fa-search mr-2"></i>Quick Search via QR / Reg. Number</button>
              <hr>
              <a href="{{route('abtc_patient_index')}}" class="btn btn-primary btn-lg"><i class="fa fa-user mr-2" aria-hidden="true"></i>Patient Lists</a>
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#nvm"><i class="fas fa-syringe mr-2"></i>New Vaccination</button>
              <a href="{{route('abtc_schedule_index')}}" class="btn btn-primary btn-lg"><i class="fas fa-calendar-alt mr-2"></i>Todays Schedule</a>
              <hr>
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#reportpanel"><i class="fas fa-chart-bar mr-2"></i>Reports</button>
              @if(auth()->user()->is_admin == 1)
              <hr>
              <a href="" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#adminpanel"><i class="fa-solid fa-lock me-2"></i>Admin Panel</a>
              @endif
              <hr>
              <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#uop"><i class="fas fa-user-cog mr-2"></i>Account Options</button>
            </div>
        </div>
        <div class="card-footer">
          <p class="text-center">Note: If errors/issues has been found or if site not working properly, please contact CESU Staff Immediately.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<form action="{{route('abtc_qr_quicksearch')}}" method="POST" autocomplete="off">
  @csrf
  <div class="modal fade" id="qs" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=""><i class="fas fa-search mr-2"></i>Quick Search via QR</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="qr" class="form-label">Scan QR Code or Type Registration Number here</label>
            <input type="text" class="form-control" name="qr" id="qr" required>
          </div>
        </div>
        <div class="modal-footer text-end">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="{{route('abtc_search_init')}}" method="POST">
  @csrf
  <div class="modal fade" id="nvm" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">New Vaccination</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

<div class="modal fade" id="adminpanel" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Admin Panel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-grid gap-2">
                <a href="{{route('abtc_vaccinationsite_index')}}" class="btn btn-primary">Vaccination Sites</a>
                <a href="{{route('abtc_vaccinebrand_index')}}" class="btn btn-primary">Vaccine Brands</a>
                <a href="" class="btn btn-primary">Site Settings</a>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reportpanel" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><b><i class="fas fa-chart-bar mr-2"></i>Reports</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="d-grid gap-2">
            <a href="{{route('abtc_report_linelist_index')}}" class="btn btn-primary">View Linelist</a>
            <a href="{{route('abtc_report_cho')}}" class="btn btn-primary">View CHO Monthly Report</a>
            <a href="{{route('abtc_dashboard')}}" class="btn btn-primary">Report Dashboard</a>
          </div>
          <p class="text-center">---------- OR ----------</p>
          <form action="{{route('abtc_report_export1')}}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="start_date" class="form-label">Start Date</label>
                  <input type="date" class="form-control" name="start_date" id="start_date" value="{{old('start_date', date('Y-m-01', strtotime('-3 Months')))}}" max="{{date('Y-m-d')}}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="end_date" class="form-label">End Date</label>
                  <input type="date" class="form-control" name="end_date" id="end_date" value="{{old('end_date', date('Y-m-d'))}}" max="{{date('Y-m-d')}}" required>
                </div>
              </div>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary" name="submit" value="AR">Export - CHO Accomplishment Report</button>
              <button type="submit" class="btn btn-primary" name="submit" value="RO4A">Export - COHORT Report</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<form action="{{route('abtc_save_settings')}}" method="POST">
  @csrf
  <div class="modal fade" id="uop" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=""><i class="fas fa-user-cog mr-2"></i>Account Options</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="default_vaccinationsite_id" class="form-label">Change Default Vaccination Site</label>
            <select class="form-select" name="default_vaccinationsite_id" id="default_vaccinationsite_id" required>
              <option value="" {{is_null(auth()->user()->abtc_default_vaccinationsite_id) ? 'selected' : ''}}>None</option>
              @foreach($vslist as $v)
              <option value="{{$v->id}}" {{($v->id == auth()->user()->abtc_default_vaccinationsite_id) ? 'selected' : ''}}>{{$v->site_name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer text-center">
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="changemenu" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Change Menu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <a href="{{route('main')}}" class="btn btn-primary btn-block">COVID-19</a>
            <hr>
            <a href="{{route('mp.home')}}" class="btn btn-primary btn-block">Monkeypox</a>
            <a href="{{route('dg.home')}}" class="btn btn-primary btn-block">Dengue</a>
          </div>
      </div>
  </div>
</div>

<script>
  //Select2 Autofocus QR Modal
  $('#qs').on('shown.bs.modal', function() {
    $('#qr').focus();
  });

  //Select2 Autofocus Fix
  $(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
  });

  $(document).ready(function () {
    $('#patient_id').select2({
          dropdownParent: $("#nvm"),
          theme: "bootstrap",
          placeholder: 'Search by Name / Patient ID ...',
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