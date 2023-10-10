@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('pharmacy_modify_process', $d->id)}}" method="POST" autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header"><b>Modify Stock</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Master Item Name</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->pharmacysupplymaster->name}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Master ID</label>
                                <input type="text" class="form-control text-center" name="" id="" value="#{{$d->pharmacysupplymaster->id}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Master SKU Code</label>
                        <input type="text" class="form-control" name="" id="" value="{{$d->pharmacysupplymaster->sku_code}}" style="text-transform: uppercase;" disabled>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Modify Stock on Branch</label>
                                <input type="text" class="form-control" name="" id="" value="{{auth()->user()->pharmacybranch->name}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Sub Item ID</label>
                                <input type="text" class="form-control text-center" name="" id="" value="#{{$d->id}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type"><b class="text-danger">*</b>Type</label>
                                <select class="form-control" name="type" id="type" required>
                                  <option value="ISSUED">ISSUED (SUBTRACT)</option>
                                  @if(!(request()->input('select_branch')))
                                  <option value="RECEIVED">RECEIVED (ADD)</option>
                                  @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="qty_type"><b class="text-danger">*</b>Quantity Type</label>
                              <select class="form-control" name="qty_type" id="qty_type" required>
                                <option value="" disabled {{(is_null(old('qty_type'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="BOX" {{(old('qty_type') == 'BOX') ? 'selected' : ''}} id="type_option_box">Per Box</option>
                                <option value="PIECE" {{(old('qty_type') == 'PIECE') ? 'selected' : ''}} id="type_option_piece">Per Piece</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select_sub_stock_id"><b class="text-danger">*</b>Select Batch</label>
                                <select class="form-control" name="select_sub_stock_id" id="select_sub_stock_id">
                                  @foreach($sub_list as $sl)
                                  <option value="{{$sl->id}}" {{(request()->input('get_substock') && request()->input('get_substock') != $sl->id) ? 'disabled' : ''}}>Batch #{{$sl->id}} - EXP Date: {{date('m/d/Y', strtotime($sl->expiration_date))}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="if_received">
                        <div class="form-group">
                            <label for="expiration_date"><b class="text-danger">*</b>Expiration Date of Received Item/s</label>
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" min="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty_to_process"><b class="text-danger">*</b>Quantity to Process (in <span id="qty_to_process_in"></span>)</label>
                                <input type="number" class="form-control" name="qty_to_process" id="qty_to_process" min="1" max="{{$d->master_box_stock}}" value="{{old('qty_to_process')}}" required>
                                <small class="text-muted">Current Amount in Stock: {{$d->master_box_stock}} {{$d->pharmacysupplymaster->getQtyType()}} {{(($d->master_piece_stock)) ? '('.$d->master_piece_stock.' Pieces)' : ''}}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_cost">Total Cost</label>
                                <input type="number" class="form-control" name="total_cost" id="total_cost">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="drsi_number">DR/SI/RIS/PTR/BL No.</label>
                                <input type="text" class="form-control" name="drsi_number" id="drsi_number" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div id="if_issuing" class="d-none">
                        <div class="form-group">
                            <label for="select_recipient"><b class="text-danger">*</b>Recipient / Transfer to</label>
                            <select class="form-control" name="select_recipient" id="select_recipient">
                              <option value="" disabled {{(is_null(old('select_recipient'))) ? 'selected' : ''}}>Choose...</option>
                              @if(auth()->user()->isAdminPharmacy())
                              <option value="BRANCH" id="selection_branch">Entities (BHS/Hospitals/Other Institutions)</option>
                              @endif
                              <!--<option value="PATIENT" id="selection_patient">Patient</option>-->
                              @if(!(request()->input('select_branch')))
                              <option value="OTHERS" id="selection_others">Others</option>
                              @endif
                            </select>
                          </div>
                          <div id="if_branch" class="d-none">
                            @if(is_null($select_branch))
                              <div class="form-group">
                                  <label for="receiving_branch_id"><b class="text-danger">*</b>Select Branch</label>
                                  <select class="form-control" name="receiving_branch_id" id="receiving_branch_id">
                                    <option value="" disabled {{(is_null(old('receiving_branch_id'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach($branch_list as $br)
                                    <option value="{{$br->id}}">{{$br->name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            @else
                                <div class="form-group">
                                    <label for="receiving_branch_id"><b class="text-danger">*</b>Select Branch</label>
                                    <select class="form-control" name="receiving_branch_id" id="receiving_branch_id" required>
                                    <option value="{{$select_branch->id}}">{{$select_branch->name}}</option>
                                    </select>
                                </div>
                            @endif
                          </div>
                          <div id="if_patient" class="d-none">
                              <div class="form-group">
                                <label for="receiving_patient_id"><b class="text-danger">*</b>Patient ID / Scan QR</label>
                                <input type="text" class="form-control" name="receiving_patient_id" id="receiving_patient_id">
                                @if($get_patient)
                                <small>Patient Selected: <b>{{$get_patient->getName()}}</b></small>
                                @endif
                              </div>
                          </div>
                          <div id="if_others" class="d-none">
                              <div class="form-group">
                                  <label for="recipient"><b class="text-danger">*</b>Specify Recipient</label>
                                  <input type="text" class="form-control" name="recipient" id="recipient">
                              </div>
                          </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks"><b id="remarks_asterisk" class="text-danger d-none">*</b>Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks" style="text-transform: uppercase;">
                    </div>
                    @if(!is_null($select_branch))
                    <input type="text" class="d-none" value="{{$select_branch->id}}" id="redirect_to_branch" name="redirect_to_branch">
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Process</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $('#receiving_branch_id').select2({
            theme: 'bootstrap',
        });
        
        var get_qtyType = '{{$d->pharmacysupplymaster->quantity_type}}';
        if(get_qtyType == 'BOX') {
            var set_maxqty = {{$d->master_box_stock}};
        } else {
            var set_maxqty = {{$d->master_piece_stock}};
        }
        
        if(get_qtyType == 'BOX') {
            $('#qty_type').val('BOX');

            if({{$d->master_piece_stock}} < {{($d->pharmacysupplymaster->config_piecePerBox) ? $d->pharmacysupplymaster->config_piecePerBox : 0}}) {
                $('#type_option_box').addClass('d-none');
            }
        }
        else {
            $('#qty_type').prop('disabled', false);
            $('#type_option_box').addClass('d-none');
            $('#qty_type').prop('required', false);
            $('#qty_type').val('PIECE');
        }

        @if(request()->input('select_branch'))
        var branch_id = {{request()->input('select_branch')}};
        $('#select_recipient').val('BRANCH').trigger('change');
        $('#receiving_branch_id').val(branch_id).trigger('change');
        @endif

        @if(request()->input('process_patient'))
            $('#select_recipient').val('PATIENT');
            $('#receiving_patient_id').val("PATIENT_{{$get_patient->qr}}");
            $('#receiving_patient_id').prop('readonly', true);
            $('#selection_branch').addClass('d-none');
            $('#selection_others').addClass('d-none');
        @endif

        $('#qty_type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'BOX') {
                $('#qty_to_process_in').text('Boxes');
            }
            else {
                $('#qty_to_process_in').text('Pieces');
            }
        }).trigger('change');

        $('#type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'ISSUED') {
                $('#select_sub_stock_id').prop('required', true);
                $('#select_sub_stock_id').prop('disabled', false);

                $('#if_received').addClass('d-none');
                $('#expiration_date').prop('required', false);

                $('#if_issuing').removeClass('d-none');
                $('#select_recipient').prop('required', true);

                $('#type_option_piece').removeClass('d-none');

                $('#remarks').prop('required', false);
                $('#remarks_asterisk').addClass('d-none');

                $('#qty_to_process').attr('max', set_maxqty);
            }
            else {
                $('#select_sub_stock_id').prop('required', false);
                $('#select_sub_stock_id').prop('disabled', true);
                $('#select_sub_stock_id').val("");

                $('#if_received').removeClass('d-none');
                $('#expiration_date').prop('required', true);

                $('#if_issuing').addClass('d-none');
                $('#select_recipient').val('');
                $('#select_recipient').prop('required', false);

                if(get_qtyType != 'PIECE') {
                    $('#qty_type').val('BOX').trigger('change');
                    $('#type_option_piece').addClass('d-none');
                }

                $('#remarks').prop('required', true);
                $('#remarks_asterisk').removeClass('d-none');

                $('#qty_to_process').removeAttr('max');
            }
        }).trigger('change');

        $('#select_recipient').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'BRANCH') {
                $('#if_branch').removeClass('d-none');
                $('#if_patient').addClass('d-none');
                $('#if_others').addClass('d-none');
                
                $('#receiving_branch_id').prop('required', true);
                $('#receiving_patient_id').prop('required', false);
                $('#recipient').prop('required', false);
            }
            else if($(this).val() == 'PATIENT') {
                $('#if_branch').addClass('d-none');
                $('#if_patient').removeClass('d-none');
                $('#if_others').addClass('d-none');
                
                $('#receiving_branch_id').prop('required', false);
                $('#receiving_patient_id').prop('required', true);
                $('#recipient').prop('required', false);
            }
            else if($(this).val() == 'OTHERS') {
                $('#if_branch').addClass('d-none');
                $('#if_patient').addClass('d-none');
                $('#if_others').removeClass('d-none');
                
                $('receiving_branch_id').prop('required', false);
                $('receiving_patient_id').prop('required', false);
                $('recipient').prop('required', true);
            }
            else {
                $('#if_branch').addClass('d-none');
                $('#if_patient').addClass('d-none');
                $('#if_others').addClass('d-none');
                
                $('receiving_branch_id').prop('required', false);
                $('receiving_patient_id').prop('required', false);
                $('recipient').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection