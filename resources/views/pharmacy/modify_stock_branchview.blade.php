@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form action="{{route('pharmacy_addCartBranch', $d->id)}}" method="POST" id="myForm">
                @csrf
                <div class="card">
                    <div class="card-header"><b>Dispense to Patient</b> (Branch: {{auth()->user()->pharmacybranch->name}})</div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                  <tr>
                                    <td>
                                      <div><b>NAME / ID:</b></div>
                                        <div>
                                            @if(auth()->user()->isAdminPharmacy())
                                            <b><a href="{{route('pharmacy_view_patient', $d->id)}}">{{$d->name}} <small>(#{{$d->id}})</small></a></b>
                                            @else
                                            <b>{{$d->name}} <small>(#{{$d->id}})</small></b>
                                            @endif
                                        </div>
                                    </td>
                                  </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Scan QR of Meds to Issue</label>
                            <input type="text" class="form-control" name="meds" id="meds" autocomplete="off" autofocus>
                        </div>
                        <div class="form-group">
                          <label for="alt_meds_id"><b class="text-danger">*</b>OR Manually Select from Inventory List</label>
                          <select class="form-control" name="alt_meds_id" id="alt_meds_id">
                            <option value="" disabled {{(is_null(old('alt_meds_id'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach($meds_list as $m)
                            <option value="{{$m->pharmacysupplymaster->sku_code}}" {{(!($m->ifHasStock())) ? 'disabled' : ''}}>{{$m->pharmacysupplymaster->name}} - {{$m->displayQty()}} {{(!($m->ifHasStock())) ? '- NO STOCK' : ''}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="type_to_process"><b class="text-danger">*</b>Type to Process</label>
                                  <select class="form-control" name="type_to_process" id="type_to_process" required>
                                    <option value="PIECE">Piece</option>
                                    <!--<option value="BOX">Box</option>-->
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty_to_process"><b class="text-danger">*</b>Quantity <span id="qty_span"></span></label>
                                    <input type="text" class="form-control" name="qty_to_process" id="qty_to_process" min="1" max="999" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="enable_override" id="enable_override" value="checkedValue"> Enable Override <i>(Ignore Quantity and Duration Limit)</i>
                          </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block" name="submit" value="add_cart">Add</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{route('pharmacy_processCartBranch', $d->id)}}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Cart</b> ({{$load_subcart->count()}})</div>
                            <div><button type="button" class="btn btn-outline-secondary" id="resetFakeBtn" {{($load_subcart->count() == 0) ? 'disabled' : ''}}>Reset/Clear</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($load_subcart->count())
                        <table class="table table-bordered table-striped text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ITEM</th>
                                    <th>QTY TO ISSUE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($load_subcart as $ind => $c)
                                <tr>
                                    <td style="vertical-align: middle;">{{$ind+1}}</td>
                                    <td style="vertical-align: middle;"><b>{{$c->pharmacysub->pharmacysupplymaster->name}}</b></td>
                                    <td style="vertical-align: middle;">{{$c->qty_to_process}} {{Str::plural($c->type_to_process, $c->qty_to_process)}}</td>
                                    <td style="vertical-align: middle;">
                                        @if($c->displayPrescriptionLimit())
                                        {{$c->displayPrescriptionLimit()}}
                                        @else
                                        <input type="number" class="form-control pcslimit" name="set_pieces_limit[]" min="1" max="900" required>
                                        @endif
                                        
                                    </td>
                                    <td style="vertical-align: middle;"><button type="submit" name="delete" value="{{$c->id}}" class="btn btn-danger deleteButton"><b>X</b></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h6 class="text-center">Cart is still empty.</h6>
                        @endif
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" name="submit" value="process" class="btn btn-success" {{($load_subcart->count() == 0) ? 'disabled' : ''}}>Finish Processing</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#alt_meds_id').select2({
        theme: 'bootstrap',
    });
</script>
@endsection