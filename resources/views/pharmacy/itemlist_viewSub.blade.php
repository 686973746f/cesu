@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Sub Item</b> (Under Branch: {{auth()->user()->pharmacybranch->name}})</div>
                    <div>
                        <a href="{{route('pharmacy_view_monthlystock', $d->id)}}" class="btn btn-primary">View Monthly Stock Table</a>
                        <a href="{{route('pharmacy_itemlist_printqr', $d->id)}}" class="btn btn-success">Print Item QR</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('pharmacy_itemlist_updateitem', $d->id)}}" method="POST">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header"><b>Item Details</b></div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Master Item Name</td>
                                        <td class="text-center">
                                            @if(auth()->user()->isPharmacyMasterAdmin())
                                            <a href="{{route('pharmacy_view_masteritem', $d->id)}}"><b>{{$d->pharmacysupplymaster->name}}</b></a>
                                            @else
                                            <b>{{$d->pharmacysupplymaster->name}}</b>
                                            @endif
                                        </td>
                                        <td class="font-weight-bold">Master ID</td>
                                        <td class="text-center">
                                            #{{$d->pharmacysupplymaster->id}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">SKU Code (Master)</td>
                                        <td class="text-center">
                                            {{$d->pharmacysupplymaster->sku_code}} {!! QrCode::size(70)->generate($d->pharmacysupplymaster->sku_code) !!}
                                        </td>
                                        <td class="font-weight-bold">SKU Code (DOH)</td>
                                        <td class="text-center">{{($d->pharmacysupplymaster->sku_code_doh) ? $d->pharmacysupplymaster->sku_code_doh : 'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Description</td>
                                        <td class="text-center" colspan="3">{{($d->pharmacysupplymaster->description) ? $d->pharmacysupplymaster->description : 'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Category</td>
                                        <td class="text-center">{{$d->pharmacysupplymaster->category}}</td>
                                        <td class="font-weight-bold">Quantity Type</td>
                                        <td class="text-center">{{$d->pharmacysupplymaster->quantity_type}}</td>
                                    </tr>
                                    <tr>
                                        <td ><b>Current Total Quantity</b></td>
                                        <td class="text-center" colspan="3">{{$d->displayQty()}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="include_inreport"><b class="text-danger">*</b>Include in Report</label>
                                      <select class="form-control" name="include_inreport" id="include_inreport" required>
                                        <option value="Y" {{(old('include_inreport', $d->include_inreport) == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('include_inreport', $d->include_inreport) == 'N') ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="alert_qtybelow"><b class="text-danger">*</b>Alert when quantity is below</label>
                                      <input type="number" class="form-control" name="alert_qtybelow" id="alert_qtybelow" value="{{old('alert_qtybelow', $d->alert_qtybelow)}}" min="0" max="9999999" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">Save</button>
                        </div>
                    </div>
                </form>
                

                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Batch Details</b></div>
                            <div><a href="{{route('pharmacy_modify_view', $d->id)}}" class="btn btn-success">New/Update Stock</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center" id="batch_tbl">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Expiration Date</th>
                                        <th>Batch #</th>
                                        <th>Source</th>
                                        <th>Current Quantity</th>
                                        <th>Date Added / By</th>
                                        <th>Date Modified / By</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sub_list as $ind => $sl)
                                    <tr>
                                        <td>{{$ind+1}}</td>
                                        <td><a href="{{route('pharmacy_view_substock', $sl->id)}}">{{date('m/d/Y (D)', strtotime($sl->expiration_date))}}</a></td>
                                        <td>{{($sl->batch_number) ? $sl->batch_number : 'N/A'}}</td>
                                        <td>{{$sl->source ?: 'N/A'}}</td>
                                        <td>{{$sl->displayQty()}}</td>
                                        <td><small>{{date('m/d/Y h:i A', strtotime($sl->created_at))}} / {{$sl->user->name}}</small></td>
                                        <td><small>{{($sl->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($sl->updated_at)).' / '.$sl->getUpdatedBy->name : 'N/A'}}</small></td>
                                        <td><a href="{{route('pharmacy_printqr_substock', $sl->id)}}" class="btn btn-success">Print QR</a></td>
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
                            <div><b>Stock Card / Transactions</b></div>
                            <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#stockcard">Download Stock Card (.XLSX)</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="card_tbl">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Received</th>
                                        <th>Issued</th>
                                        <th>Balance</th>
                                        <th>Total Cost</th>
                                        <th>DR/SI/RIS/PTR/BL No.</th>
                                        <th>Recipient/Remarks</th>
                                        <th>Processed by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scard as $ind => $s)
                                    <tr class="text-center">
                                        <td class="text-center">{{$s->id}}</td>
                                        <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
                                        <td class="text-success">{{($s->type == 'RECEIVED') ? '+ '.$s->getQtyAndType() : ''}}</td>
                                        <td class="text-danger">{{($s->type == 'ISSUED') ? '- '.$s->getQtyAndType() : ''}}</td>
                                        <td>{{$s->getBalance()}}</td>
                                        <td>{{($s->total_cost) ? $s->total_cost : 'N/A'}}</td>
                                        <td>{{($s->drsi_number) ? $s->drsi_number : 'N/A'}}</td>
                                        <td>{{$s->getRecipientAndRemarks()}}</td>
                                        <td>{{$s->user->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($scard->total() > 10)
                    <div class="card-footer text-right">
                        <a href="{{route('pharmacy_itemlist_viewmoretransactions', $d->id)}}">View More Transactions</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('pharmacy_itemlist_export_stockcard', $d->id)}}" method="POST">
        @csrf
        <div class="modal fade" id="stockcard" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Stock Cark</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="year"><b class="text-danger">*</b>Year</label>
                          <input type="number" class="form-control" name="year" id="year" value="{{date('Y')}}" min="2023" max="{{date('Y')}}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <script>
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#submitbtn').trigger('click');
                $('#submitbtn').prop('disabled', true);
                setTimeout(function() {
                    $('#submitbtn').prop('disabled', false);
                }, 2000);
                return false;
            }
        });

        $('#batch_tbl').dataTable();
    </script>
@endsection