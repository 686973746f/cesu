@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">View Item</div>
            <div class="card-body">

                <form action="{{route('pharmacy_itemlist_updateitem', $d->id)}}">
                    <div class="card mb-3">
                        <div class="card-header"><b>Item Details</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name"><b class="text-danger">*</b>Item Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" name="description" id="description">
                            </div>
                            <div class="form-group">
                                <label for="category"><b class="text-danger">*</b>Category</label>
                                <select class="form-control" name="category" id="category" required>
                                    <option value="" disabled {{is_null(old('category')) ? 'selected' : ''}}>Select...</option>
                                    <option value="ANTIBIOTICS">ANTIBIOTICS</option>
                                    <option value="FAMILY PLANNING">FAMILY PLANNING</option>
                                    <option value="MAINTENANCE">MAINTENANCE</option>
                                    <option value="OINTMENT">OINTMENT</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sku_code"><b class="text-danger">*</b>SKU Code</label>
                                <input type="text" class="form-control" name="sku_code" id="sku_code" required>
                            </div>
                            <hr>
                            <div id="accordianId" role="tablist" aria-multiselectable="true">
                                <div class="card">
                                    <div class="card-header" role="tab" id="section1HeaderId">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">Other Details</a>
                                    </div>
                                    <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="po_contract_number">PO Contract Number</label>
                                                <input type="text" class="form-control" name="po_contract_number" id="po_contract_number">
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier">Supplier</label>
                                                <input type="text" class="form-control" name="supplier" id="supplier">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dosage_form">Dosage Form</label>
                                                        <input type="text" class="form-control" name="dosage_form" id="dosage_form">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dosage_strength">Dosage Strength</label>
                                                        <input type="text" class="form-control" name="dosage_strength" id="dosage_strength">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="unit_measure">Unit Measure</label>
                                                <input type="text" class="form-control" name="unit_measure" id="unit_measure">
                                            </div>
                                            <div class="form-group">
                                                <label for="entity_name">Entity Name</label>
                                                <input type="text" class="form-control" name="entity_name" id="entity_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="source_of_funds">Source of Funds</label>
                                                <input type="text" class="form-control" name="source_of_funds" id="source_of_funds">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit_cost">Unit Cost</label>
                                                <input type="text" class="form-control" name="unit_cost" id="unit_cost">
                                            </div>
                                            <div class="form-group">
                                                <label for="mode_of_procurement">Mode of Procurement</label>
                                                <input type="text" class="form-control" name="mode_of_procurement" id="mode_of_procurement">
                                            </div>
                                            <div class="form-group">
                                                <label for="end_user">End User</label>
                                                <input type="text" class="form-control" name="end_user" id="end_user">
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="config_piecePerBox">Piece per Box</label>
                                                <input type="number" class="form-control" name="config_piecePerBox" id="config_piecePerBox">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>

                <div class="card mb-3">
                    <div class="card-header"><b>Batch Details</b></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expiration Date</th>
                                    <th>Quantity (in Boxes)</th>
                                    <th>Date Added / By</th>
                                    <th>Date Modified / By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sub_list as $ind => $sl)
                                <tr>
                                    <td>{{$ind+1}}</td>
                                    <td>{{date('Y-m-d', strtotime($sl->expiration_date))}}</td>
                                    <td>{{$sl->current_box_stock}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Stock Card / Transactions</b></div>
                            <div><button type="button" class="btn btn-success">Download</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Received</th>
                                    <th>Issued</th>
                                    <th>Balance</th>
                                    <th>Total Cost</th>
                                    <th>DR/SI/RIS/PTR/BL No.</th>
                                    <th>Recipient/Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scard as $s)
                                <tr class="text-center">
                                    <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
                                    <td>{{($s->type == 'RECEIVED') ? $s->qty_to_process : ''}}</td>
                                    <td>{{($s->type == 'ISSUED') ? $s->qty_to_process : ''}}</td>
                                    <td>{{$s->after_qty}}</td>
                                    <td>{{$s->total_cost}}</td>
                                    <td>{{$s->drsi_number}}</td>
                                    <td>{{$s->recipient}}  {{(!is_null($s->remarks)) ? '/ '.$s->remarks : ''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection