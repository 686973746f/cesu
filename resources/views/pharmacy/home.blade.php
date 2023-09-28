@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-right mb-3">
            <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkstock">Check Item Stock</button>-->
            <a href="{{route('pharmacy_itemlist')}}" class="btn btn-primary">View Inventory ({{auth()->user()->pharmacybranch->name}})</a>
            <a href="{{route('pharmacy_view_patient_list')}}" class="btn btn-primary">View Patient List</a>
            <!--<a href="{{route('pharmacy_viewreport')}}" class="btn btn-primary">Report</a>-->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#report">Report</button>
            @if(auth()->user()->isAdminPharmacy())
            <hr>
            <a href="{{route('pharmacy_masteritem_list')}}" class="btn btn-warning">View Master Item</a>
            <a href="{{route('pharmacy_list_branch')}}" class="btn btn-warning">View Branches/Entities</a>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Pharmacy Inventory System</b></div>
                    <div><b>Branch:</b> {{auth()->user()->pharmacybranch->name}}</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('pharmacy_modify_qr')}}" method="GET" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Patient ID | SKU Code | Meds QR" name="code" id="code" required autofocus>
                        <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="searchbtn"><i class="fa fa-search mr-2" aria-hidden="true"></i>Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="accordianId" role="tablist" aria-multiselectable="true">
                        <form action="{{route('pharmacy_getdispensary')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header text-center" role="tab" id="section1HeaderId">
                                    <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId">
                                        Medicine Dispensary
                                    </a>
                                </div>
                                <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                    <div class="card-body">
                                        @if(auth()->user()->isAdminPharmacy())
                                        <div class="form-group">
                                          <label for="select_branch"><b class="text-danger">*</b>Select Branch</label>
                                          <select class="form-control" name="select_branch" id="select_branch_1" required>
                                            <option value="ALL">ALL BRANCHES</option>
                                            @foreach(App\Models\PharmacyBranch::get() as $b)
                                            <option value="{{$b->id}}" {{(old('select_branch', auth()->user()->pharmacy_branch_id) == $b->id) ? 'selected' : ''}}>{{$b->name}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_date"><b class="text-danger">*</b>Start Date</label>
                                                    <input type="date" class="form-control" name="start_date" id="start_date" min="2023-01-01" value="{{date('Y-m-d')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="end_date"><b class="text-danger">*</b>End Date</label>
                                                    <input type="date" class="form-control" name="end_date" id="end_date" min="2023-01-01" value="{{date('Y-m-d')}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form action="{{route('pharmacy_viewreport')}}" method="GET">
                            <div class="card">
                                <div class="card-header text-center" role="tab" id="section2HeaderId">
                                    <a data-toggle="collapse" data-parent="#accordianId" href="#section2ContentId">
                                        Report Dashboard
                                    </a>
                                </div>
                                <div id="section2ContentId" class="collapse in" role="tabpanel" aria-labelledby="section2HeaderId">
                                    <div class="card-body">
                                        @if(auth()->user()->isAdminPharmacy())
                                            <div class="form-group">
                                              <label for="select_branch"><b class="text-danger">*</b>Select Branch</label>
                                              <select class="form-control" name="select_branch" id="select_branch_2" required>
                                                <option value="ALL">ALL BRANCHES</option>
                                                @foreach(App\Models\PharmacyBranch::get() as $b)
                                                <option value="{{$b->id}}" {{(old('select_branch', auth()->user()->pharmacy_branch_id) == $b->id) ? 'selected' : ''}}>{{$b->name}}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                                            <select class="form-control" name="year" id="year" required>
                                              @foreach(range(date('Y'), 2020) as $y)
                                                  <option value="{{$y}}">{{$y}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="type"><b class="text-danger">*</b>Select Type</label>
                                            <select class="form-control" name="type" id="type" required>
                                              <option value="" disabled selected>Choose...</option>
                                              <option value="YEARLY">YEARLY (CURRENT)</option>
                                              <option value="QUARTERLY">QUARTERLY</option>
                                              <option value="MONTHLY">MONTHLY</option>
                                              <option value="WEEKLY">WEEKLY</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="squarter">
                                            <label for="quarter"><b class="text-danger">*</b>Select Quarter</label>
                                            <select class="form-control" name="quarter" id="quarter">
                                              <option value="1">1ST QUARTER</option>
                                              <option value="2">2ND QUARTER</option>
                                              <option value="3">3RD QUARTER</option>
                                              <option value="4">4TH QUARTER</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="smonth">
                                            <label for="month"><b class="text-danger">*</b>Select Month</label>
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
                                            <label for="week"><b class="text-danger">*</b>Select Week</label>
                                            <input type="number" min="1" max="53" class="form-control" name="week" id="week" value="{{date('W')}}">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4>Loading...</h4>
                    <i class="fa fa-spinner fa-spin" aria-hidden="true" style="font-size:30px"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#searchbtn').click(function (e) { 
            $('#loading').modal('show');
        });

        $("#select_branch_1").select2({
            theme: 'bootstrap',
            dropdownParent: $('#report'),
        });

        $("#select_branch_2").select2({
            theme: 'bootstrap',
            dropdownParent: $('#report'),
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

                $('#div2').addClass('col-md-8');
                $('#div2').removeClass('col-md-4');
                $('#div3').addClass('d-none');
            }
            else if($(this).val() == 'QUARTERLY') {
                $('#squarter').removeClass('d-none');
                $('#smonth').addClass('d-none');
                $('#sweek').addClass('d-none');

                $('#quarter').prop('required', true);
                $('#month').prop('required', false);
                $('#week').prop('required', false);

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
            else if($(this).val() == 'MONTHLY') {
                $('#squarter').addClass('d-none');
                $('#smonth').removeClass('d-none');
                $('#sweek').addClass('d-none');

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
            else if($(this).val() == 'WEEKLY') {
                $('#squarter').addClass('d-none');
                $('#smonth').addClass('d-none');
                $('#sweek').removeClass('d-none');

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
        }).trigger('change');
    </script>
@endsection