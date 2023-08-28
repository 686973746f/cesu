@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('pharmacy_modify_process', request()->input('code'))}}" method="POST">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Item Name</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->name}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select_sub_supply_id">Batch ID</label>
                                <select class="form-control" name="select_sub_supply_id" id="select_sub_supply_id" required>
                                  @foreach($sub_list as $sl)
                                  <option value="{{$sl->id}}">EXP Date: {{date('m/d/Y', strtotime($sl->expiration_date))}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type"><b class="text-danger">*</b>Type</label>
                        <select class="form-control" name="type" id="type">
                          <option value="ISSUED">ISSUED</option>
                          <option value="RECEIVED">RECEIVED</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty_to_process"><b class="text-danger">*</b>Quantity (In Boxes)</label>
                                <input type="number" class="form-control" name="qty_to_process" id="qty_to_process" min="1" max="{{$d->master_box_stock}}" value="1" required>
                                <small class="text-muted">Current Amount: {{$d->master_box_stock}} Boxes</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_cost">Total Cost</label>
                                <input type="number" class="form-control" name="total_cost" id="total_cost">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="drsi_number">DR/SI/RIS/ PTR/BL No.</label>
                                <input type="text" class="form-control" name="drsi_number" id="drsi_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recipient">Recipient</label>
                                <input type="text" class="form-control" name="recipient" id="recipient">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">Process</button>
                </div>
            </div>
        </form>
    </div>
@endsection