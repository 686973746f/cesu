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
                        <form action="{{route('pharmacy_itemlist_updateitem', $d->id)}}" method="POST">
                            @csrf
                            <div id="accordianId" role="tablist" aria-multiselectable="true">
                                <div class="card">
                                    <div class="card-header" role="tab" id="section1HeaderId">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">Stock Card Details</a>
                                    </div>
                                    <div id="section1ContentId" class="collapse" role="tabpanel" aria-labelledby="section1HeaderId">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="po_contract_number">PO Contract Number</label>
                                                <input type="text" class="form-control" name="po_contract_number" id="po_contract_number" value="{{old('po_contract_number', $d->po_contract_number)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier">Supplier</label>
                                                <input type="text" class="form-control" name="supplier" id="supplier" value="{{old('supplier', $d->supplier)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dosage_form">Dosage Form</label>
                                                        <input type="text" class="form-control" name="dosage_form" id="dosage_form" value="{{old('dosage_form', $d->dosage_form)}}" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dosage_strength">Dosage Strength</label>
                                                        <input type="text" class="form-control" name="dosage_strength" id="dosage_strength" value="{{old('dosage_strength', $d->dosage_strength)}}" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="unit_measure">Unit Measure</label>
                                                <input type="text" class="form-control" name="unit_measure" id="unit_measure" value="{{old('unit_measure', $d->unit_measure)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="entity_name">Entity Name</label>
                                                <input type="text" class="form-control" name="entity_name" id="entity_name" value="{{old('entity_name', $d->entity_name)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="source_of_funds">Source of Funds</label>
                                                <input type="text" class="form-control" name="source_of_funds" id="source_of_funds" value="{{old('source_of_funds', $d->source_of_funds)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit_cost">Unit Cost</label>
                                                <input type="text" class="form-control" name="unit_cost" id="unit_cost" value="{{old('unit_cost', $d->unit_cost)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="mode_of_procurement">Mode of Procurement</label>
                                                <input type="text" class="form-control" name="mode_of_procurement" id="mode_of_procurement" value="{{old('mode_of_procurement', $d->mode_of_procurement)}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <label for="end_user">End User</label>
                                                <input type="text" class="form-control" name="end_user" id="end_user" value="{{old('end_user', $d->end_user)}}" style="text-transform: uppercase;">
                                            </div>
                                            <!--
                                            <hr>
                                            <div class="form-group">
                                                <label for="config_piecePerBox">Piece per Box</label>
                                                <input type="number" class="form-control" name="config_piecePerBox" id="config_piecePerBox" value="{{old('config_piecePerBox', $d->config_piecePerBox)}}">
                                            </div>
                                            -->
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary btn-block" id="submitbtn">Update (CTRL + S)</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                    
                </div>

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