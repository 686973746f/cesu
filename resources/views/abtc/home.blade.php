@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <div>Welcome, {{auth()->user()->name}}</div>
            <div>Date: {{date('m/d/Y')}} - Week {{date('W')}}</div>
          </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#qs"><i class="fa-solid fa-qrcode me-2"></i>Quick Search via QR</button>
              <hr>
              <a href="{{route('patient_index')}}" class="btn btn-primary btn-lg"><i class="fa-solid fa-users me-2"></i>Patient Lists</a>
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#nvm"><i class="fa-solid fa-syringe me-2"></i>New Vaccination</button>
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#reportpanel"><i class="fa-solid fa-chart-pie me-2"></i>Report</button>
              @if(auth()->user()->is_admin == 1)
              <hr>
              <a href="" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#adminpanel"><i class="fa-solid fa-lock me-2"></i>Admin Panel</a>
              @endif
              <hr>
              <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#uop"><i class="fa-solid fa-gear me-2"></i>Account Options</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<form action="{{route('qr_quicksearch')}}" method="POST" autocomplete="off">
  @csrf
  <div class="modal fade" id="qs" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=""><i class="fa-solid fa-qrcode me-2"></i>Quick Search via QR</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="qr" class="form-label">Scan QR Code here</label>
            <input type="text" class="form-control" name="qr" id="qr" required>
          </div>
        </div>
        <div class="modal-footer text-end">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form action="{{route('search_init')}}" method="POST">
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
                <a href="{{route('vaccinationsite_index')}}" class="btn btn-primary">Vaccination Sites</a>
                <a href="{{route('vaccinebrand_index')}}" class="btn btn-primary">Vaccine Brands</a>
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
        <h5 class="modal-title" id=""><i class="fa-solid fa-chart-pie me-2"></i>Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="d-grid gap-2">
            <a href="{{route('report_linelist_index')}}" class="btn btn-primary mb-3">View Linelist</a>
            <a href="{{route('report_cho')}}" class="btn btn-primary mb-3">View CHO Monthly Report</a>
          </div>
          <p class="text-center">---------- OR ----------</p>
          <form action="{{route('report_export1')}}" method="POST">
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

<form action="{{route('save_settings')}}" method="POST">
  @csrf
  <div class="modal fade" id="uop" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=""><i class="fa-solid fa-gear me-2"></i>Account Options</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="default_vaccinationsite_id" class="form-label">Default Vaccination Site</label>
            <select class="form-select" name="default_vaccinationsite_id" id="default_vaccinationsite_id" required>
              <option value="" {{is_null(auth()->user()->default_vaccinationsite_id) ? 'selected' : ''}}>None</option>
              @foreach($vslist as $v)
              <option value="{{$v->id}}" {{($v->id == auth()->user()->default_vaccinationsite_id) ? 'selected' : ''}}>{{$v->site_name}}</option>
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
              url: "{{route('patient_ajaxlist')}}",
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