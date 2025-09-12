@extends('layouts.app')

@section('content')
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
              @if(!$wastage_submit_check)
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#wastage">Input Daily Wastage</button>
              @endif
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
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#qs"><i class="fas fa-search mr-2"></i>Quick Search via QR / Reg. Number</button>
            <hr>
            <a href="{{route('abtc_patient_index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user mr-2" aria-hidden="true"></i>ABTC Patient Lists</a>
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#nvm"><i class="fas fa-syringe mr-2"></i>Search New/Existing Vaccination Details</button>
            <a href="{{route('abtc_schedule_index')}}" class="btn btn-primary btn-lg btn-block"><i class="fas fa-calendar-alt mr-2"></i>Todays Schedule</a>
            <hr>
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#reportpanel"><i class="fas fa-chart-bar mr-2"></i>ABTC Reports</button>
            <hr>
            <a href="{{route('abtcinv_home')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-flask mr-2" aria-hidden="true"></i>ABTC Inventory</a>
            <a href="{{route('abtc_financial_home')}}" class="btn btn-primary btn-lg btn-block">Financial</a>
            
            @if(auth()->user()->isAdmin == 1)
            <hr>
            <a href="" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#adminpanel"><i class="fas fa-user-lock mr-2"></i>Admin Panel</a>
            @endif
            @if(is_null(auth()->user()->abtc_default_vaccinationsite_id))
            <hr>
            <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#uop"><i class="fas fa-user-cog mr-2"></i>Account Options</button>
            @endif
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
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
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

<div class="modal fade" id="adminpanel" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Admin Panel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <a href="{{route('abtc_vaccinationsite_index')}}" class="btn btn-primary btn-block">Vaccination Sites</a>
          <a href="{{route('abtc_vaccinebrand_index')}}" class="btn btn-primary btn-block">Vaccine Brands</a>
          <a href="" class="btn btn-primary btn-block">Site Settings</a>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reportpanel" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><b><i class="fas fa-chart-bar mr-2"></i>Reports</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{route('abtc_report_linelist_index')}}" class="btn btn-primary btn-block">View Linelist/Ledger</a>
        <a href="{{route('abtc_report_cho')}}" class="btn btn-primary btn-block">View CHO Monthly Report</a>
        <a href="{{route('abtc_dashboard')}}" class="btn btn-primary btn-block">Report Dashboard</a>
        <p class="text-center mt-3">---------- OR ----------</p>
        <table class="table table-bordered table-striped text-center table-sm">
          <thead class="thead-light">
            <tr>
              <th>Quarter</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1st Quarter (Q1)</td>
              <td>{{date('01/01/Y')}} - {{date('03/t/Y')}}</td>
            </tr>
            <tr>
              <td>2nd Quarter (Q2)</td>
              <td>{{date('04/01/Y')}} - {{date('06/t/Y')}}</td>
            </tr>
            <tr>
              <td>3rd Quarter (Q3)</td>
              <td>{{date('07/01/Y')}} - {{date('09/t/Y')}}</td>
            </tr>
            <tr>
              <td>4th Quarter (Q4)</td>
              <td>{{date('10/01/Y')}} - {{date('12/t/Y')}}</td>
            </tr>
          </tbody>
        </table>
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
          <button type="submit" class="btn btn-primary btn-block" name="submit" value="AR">Export - CHO Accomplishment Report (.XLSX)</button>
            <button type="submit" class="btn btn-primary btn-block" name="submit" value="RO4A">Export - COHORT Report (.XLSX)</button>
        </form>
        <form action="{{route('abtc_report_main')}}" method="GET">
          <div class="card mt-3">
            <div class="card-header">Generate Report Template</div>
            <div class="card-body">
              <div class="form-group">
                <label for="type">Select Branch</label>
                <select class="form-control" name="branch" id="branch" required>
                  <option value="ALL">SHOW ALL</option>
                  @foreach($vslist as $v)
                  <option value="{{$v->id}}">{{$v->site_name}} ONLY</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="year">Select Year</label>
                <select class="form-control" name="year" id="year" required>
                  @foreach(range(date('Y'), 2020) as $y)
                      <option value="{{$y}}">{{$y}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="type">Select Type</label>
                <select class="form-control" name="type" id="type" required>
                  <option value="" disabled selected>Choose...</option>
                  <option value="YEARLY">YEARLY</option>
                  <option value="QUARTERLY">QUARTERLY</option>
                  <option value="MONTHLY">MONTHLY</option>
                  <option value="WEEKLY">WEEKLY</option>
                </select>
              </div>
              <div class="form-group d-none" id="squarter">
                <label for="quarter">Select Quarter</label>
                <select class="form-control" name="quarter" id="quarter">
                  <option value="1">1ST QUARTER</option>
                  <option value="2">2ND QUARTER</option>
                  <option value="3">3RD QUARTER</option>
                  <option value="4">4TH QUARTER</option>
                </select>
              </div>
              <div class="form-group d-none" id="smonth">
                <label for="month">Select Month</label>
                <select class="form-control" name="month" id="month">
                  <option value="1">JANUARY</option>
                  <option value="2">FEBRUARY</option>
                  <option value="3">MARCH</option>
                  <option value="4">APRIL</option>
                  <option value="5">MAY</option>
                  <option value="6">JUNE</option>
                  <option value="7">JULY</option>
                  <option value="8">AUGUST</option>
                  <option value="9">SEPTEMBER</option>
                  <option value="10">OCTOBER</option>
                  <option value="11">NOVEMBER</option>
                  <option value="12">DECEMBER</option>
                </select>
              </div>
              <div class="form-group d-none" id="sweek">
                <label for="week">Select Week</label>
                <input type="number" min="1" max="53" class="form-control" name="week" id="week" value="{{date('W')}}">
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Generate .DOCX File</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@if(!is_null(auth()->user()->abtc_default_vaccinationsite_id))
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
            <a href="{{route('vaxcert_home')}}" class="btn btn-primary btn-block">VaxCert Concerns</a>
            <a href="{{route('syndromic_home')}}" class="btn btn-primary btn-block">Syndromic (ITR)</a>
            <hr>
            <a href="{{route('pidsr.home')}}" class="btn btn-primary btn-block">PIDSR</a>
            <a href="{{route('fhsis_home')}}" class="btn btn-primary btn-block">eFHSIS</a>
          </div>
      </div>
  </div>
</div>
@endif

@if(!(auth()->user()->ifInitAbtcVaccineBrandDaily()))
<form action="{{route('abtc_init_vbrand')}}" method="POST">
  @csrf
  <div class="modal fade" id="select_vaccine" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Set ABTC Default Daily Values</h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="selected_vaccine"><b class="text-danger">*</b>Set Default Vaccine for Today</label>
            <select class="form-control" name="selected_vaccine" id="selected_vaccine" required>
              <option value="" disabled selected>Choose...</option>
              @foreach(App\Models\AbtcVaccineBrand::get() as $vc)
              <option value="{{$vc->id}}" {{($vc->ifHasStock()) ? '' : 'disabled'}}>{{$vc->brand_name}}{{($vc->ifHasStock()) ? '' : ' - OUT OF STOCK/DISABLED'}}</option>
              @endforeach
            </select>
          </div>
          <!--
          <div class="alert alert-info" role="alert">
            <b class="text-danger">Note:</b> Kung ang gagamiting Vaccine ay <b>OUT OF STOCK</b> na, paki-coordinate sa System Admin (CESU) para ma-initialize ang values.
          </div>
          -->
          <div class="form-group">
            <label for="vaccinator_id" class="form-label"><b class="text-danger">*</b>Set Default Vaccinator for Today</label>
            <select class="form-control" name="vaccinator_id" id="vaccinator_id" required>
                <option value="" disabled {{is_null(old('vaccinator_id')) ? 'selected' : ''}}>Choose...</option>
                @foreach(App\Models\Employee::whereNotNull('abtc_vaccinator_branch')->get() as $v)
                <option value="{{$v->id}}" {{($v->id == old('vaccinator_id')) ? 'selected' : ''}}>{{$v->getNameWithPr()}}</option>
                @endforeach
            </select>
          </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-block">Save</button>
      </div>
    </div>
  </div>
</form>

<script>
  $('#select_vaccine').modal({backdrop: 'static', keyboard: false});
  $('#select_vaccine').modal('show');
</script>
@endif

@if(!$wastage_submit_check)
<form action="{{route('abtc_init_wastage')}}" method="POST">
  @csrf
  <div class="modal fade" id="wastage" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Input Unused Bottle/s</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="wastage_dose_count"><b class="text-danger">*</b>Input Wastage (by Dose)</label>
            <input type="number" step="0.1" class="form-control" name="wastage_dose_count" id="wastage_dose_count" min="0.5" max="30" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endif
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
          placeholder: 'Search by SURNAME, FIRST NAME / Patient ID ...',
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

  $('#type').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'YEARLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', false);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'QUARTERLY') {
      $('#squarter').removeClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', true);
      $('#month').prop('required', false);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'MONTHLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').removeClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', true);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'WEEKLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').removeClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', false);
      $('#week').prop('required', true);
    }
  }).trigger('change');
</script>
@endsection