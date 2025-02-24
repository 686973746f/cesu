@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>ABTC Inventory</b></div>
                        <div>(Sorted from Newest to Oldest)</div>
                    </div>
                    <div>
                        @if(auth()->user()->isGlobalAdmin())
                        <a href="{{route('abtcinv_masterlist_home')}}" class="btn btn-outline-warning">View Masterlist</a>
                        @endif
                        <a href="{{route('abtcinv_branchinv_home')}}" class="btn btn-primary">View Inventory</a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#quickTransactionModal">Create Transaction</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Transaction Date</th>
                                <th>Facility</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Item</th>
                                <th>Batch No.</th>
                                <th>Quantity</th>
                                <th>Quantity After</th>
                                <th>Remarks</th>
                                <th>Date Posted</th>
                                <th>Encoder</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $d)
                            <tr>
                                <td class="text-center">{{$d->id}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($d->transaction_date))}}</td>
                                <td class="text-center">{{$d->stock->submaster->facility->site_name}}</td>
                                <td class="text-center">{{$d->displayType()}}</td>
                                <td class="text-center">{{$d->stock->source}}</td>
                                <td class="text-center">{{$d->stock->submaster->master->name}}</td>
                                <td class="text-center">{{$d->stock->batch_no}}</td>
                                <td class="text-center">{{$d->displayProcessQty()}}</td>
                                <td class="text-center">{{$d->after_qty}}</td>
                                <td class="text-center">{{$d->remarks ?: 'N/A'}}</td>
                                <td class="text-center">{{date('m/d/Y H:i:s', strtotime($d->created_at))}}</td>
                                <td class="text-center">{{$d->user->name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('abtcinv_process_transaction')}}" method="POST" autocomplete="off">
        @csrf
        <div class="modal fade" id="quickTransactionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Create ABTC Inventory Transaction</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="sub_id"><b class="text-danger">*</b>Select Item to Process</label>
                          <select class="form-control" name="sub_id" id="sub_id" required>
                            <option value="" disabled {{(is_null(old('sub_id'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach($qt_list as $qt)
                            <option value="{{$qt->id}}">{{$qt->master->name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div id="part2_div" class="d-none">
                            <div class="form-group">
                                <label for="transaction_type"><b class="text-danger">*</b>Transaction Type</label>
                                <select class="form-control" name="transaction_type" id="transaction_type" required>
                                  <option value="" disabled {{(is_null(old('transaction_type'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="ISSUED" {{(old('transaction_type') == 'ISSUED') ? 'selected' : ''}}>Use Stock</option>
                                  @if(auth()->user()->isAdminAbtc())
                                  <option value="RECEIVED" {{(old('transaction_type') == 'RECEIVED') ? 'selected' : ''}}>Received Stock</option>
                                  <option value="TRANSFERRED" {{(old('transaction_type') == 'TRANSFERRED') ? 'selected' : ''}}>Transfer Stock</option>
                                  @endif
                                </select>
                            </div>
                        </div>
                        <div id="transfer_div" class="d-none">
                            <div class="form-group">
                                <label for="transferto_facility"><b class="text-danger">*</b>Select ABTC Facility to Transfer</label>
                                <select class="form-control" name="transferto_facility" id="transferto_facility">
                                    <option value="" disabled {{(is_null(old('transferto_facility'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach($transfer_branches_list as $tb)
                                    <option value="{{$tb->id}}" {{(old('transferto_facility') == $tb->id) ? 'selected' : ''}}>{{$tb->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="issued_div" class="d-none">
                            <div class="form-group" id="stock_id_div">
                                <label for="stock_id"><b class="text-danger">*</b>Select Stock to Process</label>
                                <select class="form-control" name="stock_id" id="stock_id">
                                    <option value="" disabled {{(is_null(old('stock_id'))) ? 'selected' : ''}}>Choose...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="transaction_date"><b class="text-danger">*</b>Transaction Date</label>
                                <input type="date" class="form-control" name="transaction_date" id="transaction_date" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="qty_to_process"><b class="text-danger">*</b><span id="qty_string"></span></label>
                                <input type="number" class="form-control" name="qty_to_process" id="qty_to_process" min="1">
                            </div>
                        </div>
                        <div id="received_div" class="d-none">
                            <div class="form-group">
                                <label for="batch_no"><b class="text-danger">*</b>Batch No.</label>
                                <input type="text" class="form-control" name="batch_no" id="batch_no" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="expiry_date"><b class="text-danger">*</b>Expiration Date</label>
                                <input type="date" class="form-control" name="expiry_date" id="expiry_date" min="{{date('Y-m-d', strtotime('-1 Week'))}}">
                            </div>
                            <div class="form-group">
                                <label for="transaction_type"><b class="text-danger">*</b>Source</label>
                                <select class="form-control" name="source" id="source" required>
                                  <option value="" disabled {{(is_null(old('source'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="LGU" {{(old('source') == 'LGU') ? 'selected' : ''}}>LGU</option>
                                  <option value="DOH" {{(old('source') == 'DOH') ? 'selected' : ''}}>DOH</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="current_qty"><b class="text-danger">*</b>Quantity Received</label>
                                <input type="number" class="form-control" name="current_qty" id="current_qty" min="1">
                            </div>
                            <div class="form-group">
                                <label for="po_number">P.O Number</label>
                                <input type="text" class="form-control" name="po_number" id="po_number" value="{{old('po_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="unit_price"><b class="text-danger">*</b>Unit Price</label>
                                <input type="number" step="0.01" class="form-control" name="unit_price" id="unit_price" min="1">
                            </div>
                            <div class="form-group">
                              <label for="remarks">Remarks</label>
                              <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
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

    <script>
        $('#sub_id').select2({
            theme: 'bootstrap',
        });

        $('#stock_id').select2({
            theme: 'bootstrap',
            dropdownParent: $("#stock_id_div"),
        });

        $('#sub_id').change(function (e) { 
            e.preventDefault();
            let sub_id = $(this).val();

            if(sub_id) {
                $('#part2_div').removeClass('d-none');
            }
            else {
                $('#part2_div').addClass('d-none');
            }
        });

        $('#transaction_type').change(function (e) { 
            e.preventDefault();

            let sub_id = $('#sub_id').val();
            
            if($(this).val() == 'ISSUED') {
                $('#issued_div').removeClass('d-none');
                $('#received_div').addClass('d-none');

                $('#stock_id').prop('required', true);
                $('#qty_to_process').prop('required', true);
                $('#transaction_date').prop('required', true);

                $('#batch_no').prop('required', false);
                $('#expiry_date').prop('required', false);
                $('#source').prop('required', false);
                $('#current_qty').prop('required', false);
                $('#unit_price').prop('required', false);

                $('#transfer_div').addClass('d-none');
                $('#transferto_facility').prop('required', false);

                $('#qty_string').text('Quantity Used');

                $.ajax({
                    url: `/abtc_inventory/get_stocks_list/${sub_id}`,
                    type: 'GET',
                    success: function (data) {
                        if (data.length > 0) {
                            data.forEach(stock => {
                                $('#stock_id').append(
                                    `<option value="${stock.id}">${stock.text}</option>`
                                );
                            });
                        } else {
                            alert('No stocks found for the selected masterlist.');
                        }
                    },
                    error: function () {
                        alert('Failed to fetch stocks. Please try again.');
                    }
                });
            }
            else if($(this).val() == 'RECEIVED') {
                $('#issued_div').addClass('d-none');
                $('#received_div').removeClass('d-none');

                $('#stock_id').prop('required', false);
                $('#qty_to_process').prop('required', false);
                $('#transaction_date').prop('required', false);

                $('#batch_no').prop('required', true);
                $('#expiry_date').prop('required', true);
                $('#source').prop('required', true);
                $('#current_qty').prop('required', true);
                $('#unit_price').prop('required', true);
                //$('#remarks').prop('required', true);

                $('#transfer_div').addClass('d-none');
                $('#transferto_facility').prop('required', false);
            }
            else if($(this).val() == 'TRANSFERRED') {
                //Same as Issued Div but Branch will be selected
                $('#transfer_div').removeClass('d-none');
                $('#transferto_facility').prop('required', true);
                $('#issued_div').removeClass('d-none');
                $('#received_div').addClass('d-none');

                $('#stock_id').prop('required', true);
                $('#qty_to_process').prop('required', true);
                $('#transaction_date').prop('required', true);

                $('#batch_no').prop('required', false);
                $('#expiry_date').prop('required', false);
                $('#source').prop('required', false);
                $('#current_qty').prop('required', false);
                $('#unit_price').prop('required', false);

                $('#qty_string').text('Quantity to Transfer');

                $.ajax({
                    url: `/abtc_inventory/get_stocks_list/${sub_id}`,
                    type: 'GET',
                    success: function (data) {
                        if (data.length > 0) {
                            data.forEach(stock => {
                                $('#stock_id').append(
                                    `<option value="${stock.id}">${stock.text}</option>`
                                );
                            });
                        } else {
                            alert('No stocks found for the selected masterlist.');
                        }
                    },
                    error: function () {
                        alert('Failed to fetch stocks. Please try again.');
                    }
                });
            }
        });
    </script>
@endsection