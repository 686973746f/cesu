@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-right mb-3">
            <div>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newTransaction">New Transaction</button>
                @if(auth()->user()->isPharmacyBranchAdminOrMasterAdmin())
                <a href="{{route('pharmacy_pending_transaction_view')}}" class="btn btn-warning">Pending Transactions</a>
                @if(auth()->user()->isPharmacyMasterAdmin())
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#switchPharmacy">Switch Pharmacy</button>
                @endif
                @endif
            </div>
            <div class="mt-3">
                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkstock">Check Item Stock</button>-->
                <a href="{{route('pharmacy_itemlist')}}" class="btn btn-primary">View Inventory ({{auth()->user()->pharmacybranch->name}})</a>
                <a href="{{route('pharmacy_view_patient_list')}}" class="btn btn-primary">Patients</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#report">Report</button>
            </div>
            @if(auth()->user()->isPharmacyMasterAdmin())
            <div class="mt-3">
                <a href="{{route('pharmacy_masteritem_list')}}" class="btn btn-warning">Medicines Masterlist</a>
                <a href="{{route('pharmacy_list_branch')}}" class="btn btn-warning">Branches/Entities</a>
            </div>
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
                @if($expired_list->count() != 0)
                <div class="alert alert-warning" role="alert">
                    <h4><b>Medicine Near Expiration Warning</b></h4>
                    <h6>Please check the list below:</h6>
                    <hr>
                    <ul>
                        @foreach($expired_list as $ei)
                        <li><a href="{{route('pharmacy_itemlist_viewitem', $ei->pharmacysub->id)}}"><b>{{$ei->pharmacysub->pharmacysupplymaster->name}}</b></a> - {{$ei->pharmacysub->displayQty()}} - Expires in: {{date('M d, Y (D)', strtotime($ei->expiration_date))}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{route('pharmacy_modify_qr')}}" method="GET" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Patient ID | SKU Code | Meds QR" name="code" id="code" autocomplete="off" required autofocus>
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
                                        @if(auth()->user()->isPharmacyMasterAdmin())
                                        <div class="form-group">
                                          <label for="select_branch"><b class="text-danger">*</b>Select Branch</label>
                                          <select class="form-control" name="select_branch" id="select_branch_1" required>
                                            <option value="ALL">ALL BRANCHES</option>
                                            @foreach(App\Models\PharmacyBranch::where('enabled', 1)->get() as $b)
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
                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="generateV1">Generate</button>
                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="generateV2">Generate (Version 2)</button>
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
                                        @if(auth()->user()->isPharmacyMasterAdmin())
                                            <div class="form-group">
                                              <label for="select_branch"><b class="text-danger">*</b>Select Branch</label>
                                              <select class="form-control" name="select_branch" id="select_branch_2" required>
                                                <option value="ALL">ALL BRANCHES</option>
                                                @foreach(App\Models\PharmacyBranch::where('enabled', 1)->get() as $b)
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
                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="view_report">Submit</button>
                                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="generate_inoutreport">Generate In/Out Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('switch_pharmacy') }}" method="POST">
        @csrf
        <div class="modal fade" id="switchPharmacy" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Switch Pharmacy</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div id="change_branch_div">
                            <div class="form-group">
                                <label for="pharmacy_branch_id"><b class="text-danger">*</b>Select Branch to Switch</label>
                                <select class="form-control" name="pharmacy_branch_id" id="pharmacy_branch_id" required>
                                  <option value="" disabled {{(is_null(old('pharmacy_branch_id'))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach($branch_list as $br)
                                  <option value="{{$br->id}}">{{$br->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('pharmacy_processtransactionv2') }}" method="POST">
        @csrf
        <input type="hidden" name="request_uuid" value="{{ Str::uuid() }}">
        <div class="modal fade" id="newTransaction" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        @if(session('nt_msg'))
                        <div class="alert alert-{{session('nt_msgtype')}} text-center" role="alert">
                            {{session('nt_msg')}}
                        </div>
                        @endif
                        <div id="subMedicineList">
                            <div class="form-group">
                              <label for="nt_substock_id"><b class="text-danger">*</b>Select Medicine</label>
                              <select class="form-control" name="nt_substock_id" id="nt_substock_id" required>
                                <option disabled selected value="">Choose...</option>
                              </select>
                            </div>
                        </div>
                        <div id="part2_div">
                            <div class="form-group">
                              <label for="nt_transaction_type"><b class="text-danger">*</b>Select Type of Transaction</label>
                              <select class="form-control" name="nt_transaction_type" id="nt_transaction_type" required>
                                <option disabled selected value="">Choose...</option>
                                <option value="RECEIVED">Add/Receive Stock</option>
                                <option value="TRANSFER">Transfer Stock</option>
                              </select>
                            </div>
                        </div>

                        <div id="eitherway_div" class="d-none">
                            <div id="nt_bn_div">
                                <div class="form-group">
                                    <label for="nt_bn"><b class="text-danger">*</b>Select Batch No. and Source</label>
                                    <select class="form-control" name="nt_bn" id="nt_bn">
                                        <option disabled selected value="">Choose...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="receive_div" class="d-none">
                            <div id="nt_newbn_div" class="d-none">
                                <div class="form-group">
                                  <label for="nt_new_batchno"><b class="text-danger">*</b>Input New Batch No.</label>
                                  <input type="text" class="form-control" name="nt_new_batchno" id="nt_new_batchno" value="{{ old('nt_new_batchno') }}">
                                </div>
                                <div class="form-group">
                                    <label for="nt_new_expiration_date"><b class="text-danger">*</b>Expiration Date</label>
                                    <input type="date" class="form-control" name="nt_new_expiration_date" id="nt_new_expiration_date" value="{{old('nt_new_expiration_date')}}" value="{{ date('Y-m-d', strtotime('-1 Week')) }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nt_new_stock_source"><b class="text-danger">*</b>Source</label>
                                            <select class="form-control" name="nt_new_stock_source" id="nt_new_stock_source">
                                                <option value="" disabled {{(is_null(old('nt_new_stock_source'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="DONATION" {{(old('nt_new_stock_source') == 'DONATION') ? 'selected' : ''}}>Donation</option>
                                                <option value="INITIALBALANCE" {{(old('nt_new_stock_source') == 'INITIALBALANCE') ? 'selected' : ''}}>Initial Balance</option>
                                                <option value="PROCURED" {{(old('nt_new_stock_source') == 'PROCURED') ? 'selected' : ''}}>Procured</option>
                                                <option value="RECEIVED" {{(old('nt_new_stock_source') == 'RECEIVED') ? 'selected' : ''}}>Received</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nt_new_source"><b class="text-danger">*</b>Procured By</label>
                                            <select class="form-control" name="nt_new_source" id="nt_new_source">
                                                <option value="" disabled {{(is_null(old('nt_new_source'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="LGU" {{(old('nt_new_source') == 'LGU') ? 'selected' : ''}}>LGU</option>
                                                <option value="DOH" {{(old('nt_new_source') == 'DOH') ? 'selected' : ''}}>DOH</option>
                                                <option value="OTHERS" {{(old('nt_new_source') == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="nt_new_othersource_div">
                                            <label for="nt_new_othersource_name"><b class="text-danger">*</b>Input Other Source</label>
                                            <input type="text" class="form-control" name="nt_new_othersource_name" id="nt_new_othersource_name" style="text-transform: uppercase;" value="{{old('nt_new_othersource_name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="part2_5div" class="d-none">
                            <div class="form-group">
                                <label for="qty_to_process"><b class="text-danger">*</b><span id="qty_label"></span></label>
                                <input type="number" class="form-control" name="qty_to_process" id="qty_to_process" value="{{old('qty_to_process')}}">
                            </div>
                            <div id="transfer_div" class="d-none">
                                <div class="form-group">
                                    <label for="nt_recipient"><b class="text-danger">*</b>Recipient / Transfer to</label>
                                    <select class="form-control" name="nt_recipient" id="nt_recipient">
                                      <option value="" disabled {{(is_null(old('nt_recipient'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="BRANCH">Entities (BHS/Hospitals/Other Institutions)</option>
                                      <option value="OTHERS" id="selection_others">Others</option>
                                    </select>
                                </div>
                                <div id="transfer_branch_div" class="d-none">
                                    <div class="form-group">
                                        <label for="nt_tr_branch_id"><b class="text-danger">*</b>Select Branch</label>
                                        <select class="form-control" name="nt_tr_branch_id" id="nt_tr_branch_id">
                                          <option value="" disabled {{(is_null(old('nt_tr_branch_id'))) ? 'selected' : ''}}>Choose...</option>
                                          @foreach($branch_list as $br)
                                          <option value="{{$br->id}}">{{$br->name}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="transfer_others_div" class="d-none">
                                    <div class="form-group">
                                        <label for="nt_tr_others"><b class="text-danger">*</b>Specify Recipient</label>
                                        <input type="text" class="form-control" name="nt_tr_others" id="nt_tr_others">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="drsi_number">DR/SI/RIS/PTR/BL No.</label>
                                <input type="text" class="form-control" name="drsi_number" id="drsi_number" value="{{old('drsi_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="total_cost">Unit Cost</label>
                                <input type="number" step="0.01" class="form-control" name="total_cost" id="total_cost" min="1" value="{{old('total_cost')}}">
                            </div>
                        </div>
                        
                        <div id="part3_div" class="d-none">
                            <hr>
                            <div class="form-group">
                                <label for="nt_remarks"><b class="text-danger" id="remarks_ast">*</b>Remarks</label>
                                <textarea class="form-control" name="nt_remarks" id="nt_remarks" rows="3">{{old('nt_remarks')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

        $("#nt_substock_id").select2({
            theme: 'bootstrap',
            dropdownParent: $('#subMedicineList'),
        });

        $("#nt_bn").select2({
            theme: 'bootstrap',
            dropdownParent: $('#nt_bn_div'),
        });

        $("#nt_tr_branch_id").select2({
            theme: 'bootstrap',
            dropdownParent: $('#transfer_branch_div'),
        });

        $("#pharmacy_branch_id").select2({
            theme: 'bootstrap',
            dropdownParent: $('#change_branch_div'),
        });

        function getUrlParam(name) {
            return new URLSearchParams(window.location.search).get(name);
        }

        $(document).ready(function () {
            let defaultSubstockId = getUrlParam('transact_substock_id');

            $.get("{{ route('ajax.pharmacy-supply-subs') }}", function (data) {

                let select = $('#nt_substock_id');

                $.each(data, function (i, item) {
                    select.append(
                        `<option value="${item.id}">${item.name}</option>`
                    );
                });

                if (defaultSubstockId) {
                    $('#newTransaction').modal('show');
                    $('#nt_substock_id').val(defaultSubstockId).trigger('change');
                }
            });

            @if(session('nt_msg'))
            $('#newTransaction').modal('show');
            @endif
        });
        

        $('#nt_substock_id').change(function (e) { 
            e.preventDefault();
            let value = $(this).val();

            $('#part2_div').addClass('d-none');
            $('#part2_5div').addClass('d-none');

            if(value) {
                $('#part2_div').removeClass('d-none');
                $('#nt_transaction_type').val('').trigger('change');
            }
        }).trigger('change');

        $('#nt_transaction_type').change(function (e) { 
            e.preventDefault();
            
            $('#transfer_div').addClass('d-none');
            $('#nt_recipient').prop('required', false);
            $('#receive_div').addClass('d-none');
            $('#qty_to_process').prop('required', false);

            $('#part3_div').addClass('d-none');
            $('#nt_remarks').prop('required', false);
            $('#remarks_ast').addClass('d-none');

            $('#eitherway_div').addClass('d-none');
            
            let value = $(this).val();
            if(value == 'RECEIVED') {
                $('#part2_5div').removeClass('d-none');
                $('#qty_label').text('Quantity to Receive (in Piece/s)');
                $('#eitherway_div').removeClass('d-none');
                $('#part3_div').removeClass('d-none');
                $('#remarks_ast').removeClass('d-none');
                $('#nt_remarks').prop('required', true);

                $('#receive_div').removeClass('d-none');

                $('#nt_bn').empty().append(
                    `<option value="" disabled selected>Choose...</option>`
                );

                $.get("{{ route('ajax.pharmacy-sub-stocks') }}", {
                    subsupply_id: $('#nt_substock_id').val(),
                    transfer_type: value,
                }, function (data) {

                    $.each(data, function (i, batch) {
                        $('#nt_bn').append(
                            `<option value="${batch.id}">
                                ${batch.batch_number} - ${batch.source} (${batch.current_piece_stock} PCS | Exp: ${batch.expiration_date})
                            </option>`
                        );
                    });

                    $('#nt_bn').append(
                        `<option value="N/A">NOT LISTED (N/A)</option>`
                    );
                });

                $('#qty_to_process').prop('required', true);
            }
            else if(value == 'TRANSFER') {
                $('#part2_5div').removeClass('d-none');
                $('#qty_label').text('Quantity to Dispense (in Piece/s)');
                $('#eitherway_div').removeClass('d-none');
                $('#transfer_div').removeClass('d-none');
                $('#nt_recipient').prop('required', true);
                $('#part3_div').removeClass('d-none');

                $('#nt_bn').empty().append(
                    `<option value="" disabled selected>Choose...</option>`
                );

                $.get("{{ route('ajax.pharmacy-sub-stocks') }}", {
                    subsupply_id: $('#nt_substock_id').val(),
                    transfer_type: value,
                }, function (data) {

                    $.each(data, function (i, batch) {
                        $('#nt_bn').append(
                            `<option value="${batch.id}">
                                ${batch.batch_number} - ${batch.source} (${batch.current_piece_stock} PCS | Exp: ${batch.expiration_date})
                            </option>`
                        );
                    });
                });

                $('#qty_to_process').prop('required', true);
            }
        }).trigger('change');

        $('#nt_bn').change(function (e) { 
            e.preventDefault();
            $('#nt_new_batchno').prop('required', false);
            $('#nt_new_stock_source').prop('required', false);
            $('#nt_new_source').prop('required', false);
            $('#nt_newbn_div').addClass('d-none');

            if($(this).val() == 'N/A' && $('#nt_transaction_type').val() == 'RECEIVED') {
                $('#nt_newbn_div').removeClass('d-none');
                $('#nt_new_batchno').prop('required', true);
                $('#nt_new_stock_source').prop('required', true);
                $('#nt_new_source').prop('required', true);
            }
        });

        $('#nt_new_source').change(function (e) { 
            e.preventDefault();
            
            if($(this).val() == 'OTHERS') {
                $('#nt_new_othersource_div').removeClass('d-none');
                $('#nt_new_othersource_name').prop('required', true);
            }
            else {
                $('#nt_new_othersource_div').addClass('d-none');
                $('#nt_new_othersource_name').prop('required', false);
            }
        }).trigger('change');

        $('#nt_recipient').change(function (e) { 
            e.preventDefault();
            $('#transfer_branch_div').addClass('d-none');
            $('#nt_tr_branch_id').prop('required', false);

            $('#transfer_others_div').addClass('d-none');
            $('#nt_tr_others').prop('required', false);

            if($(this).val() == 'BRANCH') {
                $('#transfer_branch_div').removeClass('d-none');
                $('#nt_tr_branch_id').prop('required', true);
            }
            else if($(this).val() == 'OTHERS') {
                $('#transfer_others_div').removeClass('d-none');
                $('#nt_tr_others').prop('required', true);
            }
        });
    </script>
@endsection