@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>ABTC Inventory</b></div>
                    <div>
                        @if(auth()->user()->isGlobalAdmin())
                        <a href="{{route('abtcinv_masterlist_home')}}" class="btn btn-primary">View Masterlist</a>
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

                
            </div>
        </div>
    </div>

    <form action="{{route('abtcinv_process_transaction')}}" method="POST">
        @csrf
        <div class="modal fade" id="quickTransactionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create ABTC Inventory Transaction</h5>
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
                                  <option value="RECEIVED" {{(old('transaction_type') == 'RECEIVED') ? 'selected' : ''}}>Received Stock</option>
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
                                <input type="date" class="form-control" name="transaction_date" id="transaction_date" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="qty_to_process"><b class="text-danger">*</b>Amount</label>
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
                                <label for="current_qty"><b class="text-danger">*</b>Amount</label>
                                <input type="number" class="form-control" name="current_qty" id="current_qty" min="1">
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
                        <button type="submit" class="btn btn-success">Submit</button>
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
            }
        });
    </script>
@endsection