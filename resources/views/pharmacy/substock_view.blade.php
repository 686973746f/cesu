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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock_source"><b class="text-danger">*</b>Source</label>
                            <select class="form-control" name="stock_source" id="stock_source" required>
                                <option value="" disabled {{(is_null(old('stock_source', $d->stock_source))) ? 'selected' : ''}}>Choose...</option>
                                <option value="DONATION" {{(old('stock_source', $d->stock_source) == 'DONATION') ? 'selected' : ''}}>Donation</option>
                                <option value="INITIALBALANCE" {{(old('stock_source', $d->stock_source) == 'INITIALBALANCE') ? 'selected' : ''}}>Initial Balance</option>
                                <option value="PROCURED" {{(old('stock_source', $d->stock_source) == 'PROCURED') ? 'selected' : ''}}>Procured</option>
                                <option value="RECEIVED" {{(old('stock_source', $d->stock_source) == 'RECEIVED') ? 'selected' : ''}}>Received</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiration_date"><b class="text-danger">*</b>Procured By</label>
                            <select class="form-control" name="source" id="source" required>
                                <option value="" disabled {{(is_null(old('source', $d->source))) ? 'selected' : ''}}>Choose...</option>
                                <option value="LGU" {{(old('source', $d->source) == 'LGU') ? 'selected' : ''}}>LGU</option>
                                <option value="DOH" {{(old('source', $d->source) == 'DOH') ? 'selected' : ''}}>DOH</option>
                                <option value="OTHERS" {{(old('source', $d->source) == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="othersource_div">
                            <label for="othersource_name"><b class="text-danger">*</b>Input Other Source</label>
                            <input type="text" class="form-control" name="othersource_name" id="othersource_name" style="text-transform: uppercase;" value="{{old('batch_number')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiration_date"><b class="text-danger">*</b>Expiration Date</label>
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="{{old('expiration_date', $d->expiration_date)}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch_number"><b class="text-danger">*</b>Batch Number</label>
                            <input type="text" class="form-control" name="batch_number" id="batch_number" value="{{old('batch_number', $d->batch_number)}}" style="text-transform: uppercase" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="display_qty">Current Quantity (in Piece/s)</label>
                  <input type="number" class="form-control" name="display_qty" id="display_qty" value="{{$d->current_piece_stock}}" disabled>
                </div>
                <div class="form-group">
                  <label for="adjust_stock"><b class="text-danger">*</b>Adjust Stock?</label>
                  <select class="form-control" name="adjust_stock" id="adjust_stock" required>
                    <option value="N" {{(old('adjust_stock') == 'N') ? 'selected' : ''}}>No</option>
                    <option value="Y" {{(old('adjust_stock') == 'Y') ? 'selected' : ''}}>Yes</option>
                  </select>
                </div>
                <div id="adjustment_div" class="d-none">
                    <hr>
                    <div class="form-group">
                        <label for="adjustment_qty"><b class="text-danger">*</b>Adjust Quantity to (in Piece/s)</label>
                        <input type="number" class="form-control" name="adjustment_qty" id="adjustment_qty" value="{{old('adjustment_qty', $d->current_piece_stock)}}">
                    </div>
                    <div class="form-group">
                        <label for="adjustment_reason"><b class="text-danger">*</b>Reason for Adjustment (Required)</label>
                        <textarea class="form-control" name="adjustment_reason" id="adjustment_reason" rows="3">{{old('adjustment_reason')}}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>

<script>
    $('#adjust_stock').change(function (e) { 
        e.preventDefault();

        $('#adjustment_div').addClass('d-none');
        $('#adjustment_qty').prop('required', false);
        $('#adjustment_reason').prop('required', false);

        if($(this).val() == 'Y') {
            $('#adjustment_div').removeClass('d-none');
            $('#adjustment_qty').prop('required', true);
            $('#adjustment_reason').prop('required', true);
        }
    }).trigger('change');

    $('#source').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'OTHERS') {
            $('#othersource_div').removeClass('d-none');
            $('#othersource_name').prop('required', true);
        }
        else {
            $('#othersource_div').addClass('d-none');
            $('#othersource_name').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection