@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pharmacy_update_masteritem', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Manage Master Item</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="bg-light">Created at / By</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}} / {{$d->user->name}}</td>
                            <td class="bg-light">Updated at / By</td>
                            <td class="text-center">{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->updated_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="form-group">
                  <label for="enabled"><b class="text-danger">*</b>Enabled</label>
                  <select class="form-control" name="enabled" id="enabled" required>
                    <option value="1" {{(old('enabled', $d->enabled) == 1) ? 'selected' : ''}}>Yes</option>
                    <option value="0" {{(old('enabled', $d->enabled) == 0) ? 'selected' : ''}}>No</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="name"><b class="text-danger">*</b>Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku_code"><b class="text-danger">*</b>Master SKU Code</label>
                            <input type="text" class="form-control" name="sku_code" id="sku_code" value="{{old('sku_code', $d->sku_code)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku_code_doh">DOH SKU Code</label>
                            <input type="text" class="form-control" name="sku_code_doh" id="sku_code_doh" value="{{old('sku_code_doh', $d->sku_code_doh)}}">
                          </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}">
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category"><b class="text-danger">*</b>Category</label>
                            <select class="form-control" name="category" id="category" required>
                                <option value="GENERAL" {{(old('category', $d->category) == 'GENERAL') ? 'selected' : ''}}>GENERAL</option>
                                <option value="ANTIBIOTICS" {{(old('category', $d->category) == 'ANTIBIOTICS') ? 'selected' : ''}}>ANTIBIOTICS</option>
                                <option value="BOTTLES" {{(old('category', $d->category) == 'BOTTLES') ? 'selected' : ''}}>BOTTLES</option>
                                <option value="FAMILY PLANNING" {{(old('category', $d->category) == 'FAMILY PLANNING') ? 'selected' : ''}}>FAMILY PLANNING</option>
                                <option value="MAINTENANCE" {{(old('category', $d->category) == 'MAINTENANCE') ? 'selected' : ''}}>MAINTENANCE</option>
                                <option value="OINTMENT" {{(old('category', $d->category) == 'OINTMENT') ? 'selected' : ''}}>OINTMENT</option>
                                <option value="YELLOW RX" {{(old('category', $d->category) == 'YELLOW RX') ? 'selected' : ''}}>YELLOW RX</option>
                                <option value="OTHERS" {{(old('category', $d->category) == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity_type"><b class="text-danger">*</b>Quantity Type</label>
                            <select class="form-control" name="quantity_type" id="quantity_type" required>
                                <option value="" disabled {{(is_null(old('quantity_type', $d->quantity_type)) ? 'selected' : '')}}>Choose...</option>
                                <option value="PIECE" {{(old('quantity_type', $d->quantity_type) == 'PIECE') ? 'selected' : ''}}>PER PIECE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="maxpiece_div">
                            <label for="config_piecePerBox"><b class="text-danger">*</b>Max pieces inside per Box</label>
                            <input type="number" class="form-control" name="config_piecePerBox" id="config_piecePerBox" min="1" value="{{old('config_piecePerBox', $d->config_piecePerBox)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="master_alert_qtybelow"><b class="text-danger">*</b>Alert when Quantity is below</label>
                          <input type="number" class="form-control" id="master_alert_qtybelow" name="master_alert_percent" value="{{old('master_alert_percent', $d->master_alert_percent)}}" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="usage_category">Usage Category</label>
                  <select class="form-control" name="usage_category[]" id="usage_category" multiple>
                    @foreach(App\Models\PharmacyPatient::getReasonList() as $rea)
                        <option value="{{$rea}}" {{(in_array($rea, explode(',', old('usage_category', $d->usage_category)))) ? 'selected' : ''}}>{{$rea}}</option>
                    @endforeach
                  </select>
                </div>
                <hr>
                <div class="card">
                    <div class="card-header">Restriction Duration Settings for Patients</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_days">Duration of Reset in Days <i>(Leave Blank if Not Applicable)</i></label>
                                    <input type="duration_days" class="form-control" name="duration_days" id="duration_days" min="1" value="{{old('duration_days', $d->duration_days)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($d->quantity_type == 'BOX')
                                <div class="form-group">
                                    <label for="maxbox_perduration">Maximum Amount of BOXES to Request after Duration Reset <i>(Leave Blank if Not Applicable)</i></label>
                                    <input type="duration_days" class="form-control" name="maxbox_perduration" id="maxbox_perduration" min="1" value="{{old('maxbox_perduration', $d->maxbox_perduration)}}">
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="name">Maximum Amount of PIECES to Request after Duration Reset <i>(Leave Blank if Empty)</i></label>
                                    <input type="maxpiece_perduration" class="form-control" name="maxpiece_perduration" id="maxpiece_perduration" min="1" value="{{old('maxpiece_perduration', $d->maxpiece_perduration)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).bind('keydown', function(e) {
        if(e.ctrlKey && (e.which == 83)) {
            e.preventDefault();
            $('#submitBtn').trigger('click');
            $('#submitBtn').prop('disabled', true);
            setTimeout(function() {
                $('#submitBtn').prop('disabled', false);
            }, 2000);
            return false;
        }
    });

    $('#usage_category').select2({
        theme: 'bootstrap',
    });
</script>
@endsection