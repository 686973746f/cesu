@extends('layouts.app')

@section('content')
<div class="container">
    <form action="" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Modify Sub Stock</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="bg-light">SubStock ID</td>
                            <td class="text-center">#{{$d->id}}</td>
                            <td class="bg-light">Item Name</td>
                            <td class="text-center">{{$d->pharmacysub->pharmacysupplymaster->name}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Created At / By</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}} / {{$d->user->name}}</td>
                            <td class="bg-light">Updated At / By</td>
                            <td class="text-center">{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->updated_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="expiration_date"><b class="text-danger">*</b>Expiration Date</label>
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="{{old('expiration_date', $d->expiration_date)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="batch_number">Batch Number</label>
                            <input type="text" class="form-control" name="batch_number" id="batch_number" value="{{old('batch_number', $d->batch_number)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lot_number">Lot Number</label>
                            <input type="text" class="form-control" name="lot_number" id="lot_number" value="{{old('lot_number', $d->lot_number)}}">
                        </div>
                    </div>
                </div>
                @if($d->pharmacysub->pharmacysupplymaster->quantity_type == 'BOX')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="change_qty_box">Current Quantity (in Box)</label>
                            <input type="number" class="form-control" name="change_qty_box" id="change_qty_box" value="{{old('change_qty_box', $d->current_box_stock)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="change_qty_piece">Current Quantity (in Piece/s)</label>
                            <input type="number" class="form-control" name="change_qty_piece" id="change_qty_piece" value="{{old('change_qty_piece', $d->current_piece_stock)}}" readonly>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                  <label for="change_qty_piece">Current Quantity (in Piece/s)</label>
                  <input type="number" class="form-control" name="change_qty_piece" id="change_qty_piece" value="{{old('change_qty_piece', $d->current_piece_stock)}}" required>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection