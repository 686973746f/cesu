@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="{{route('pharmacy_patient_addcart', $d->id)}}" method="POST" id="myForm">
                    @csrf
                    <div class="card">
                        <div class="card-header"><b>Issuance of Meds to Patient</b> (Branch: {{auth()->user()->pharmacybranch->name}})</div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                                {{session('msg')}}
                            </div>
                            @endif
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bg-light">Name of Patient</td>
                                        <td class="text-center"><b><a href="{{route('pharmacy_view_patient', $d->id)}}">{{$d->getName()}}</a></b></td>
                                        <td class="bg-light">Patient ID</td>
                                        <td class="text-center">#{{$d->id}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light">Age / Sex</td>
                                        <td class="text-center">{{$d->getAge()}} / {{$d->sg()}}</td>
                                        <td class="bg-light">Birthdate</td>
                                        <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <div class="form-group">
                                <label for="">Scan QR of Meds to Issue</label>
                                <input type="text" class="form-control" name="meds" id="meds" autocomplete="off" autofocus>
                            </div>
                            <div class="form-group">
                              <label for="alt_meds_id">OR Manually Select from Inventory List</label>
                              <select class="form-control" name="alt_meds_id" id="alt_meds_id">
                                <option value="" disabled {{(is_null(old('alt_meds_id'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach($meds_list as $m)
                                <option value="{{$m->pharmacysupplymaster->sku_code}}">{{$m->pharmacysupplymaster->name}} - {{$m->displayQty()}}</option>
                                @endforeach
                              </select>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="type_to_process">Type to Process</label>
                                      <select class="form-control" name="type_to_process" id="type_to_process" required>
                                        <option value="PIECE">Piece</option>
                                        <option value="BOX">Box</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="qty">Quantity <span id="qty_span"></span></label>
                                        <input type="text" class="form-control" name="qty" id="qty" min="1" max="999" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Add</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{route('pharmacy_patient_process_cart', $d->id)}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div><b>Cart</b> ({{$load_subcart->count()}})</div>
                                <div><button type="submit" class="btn btn-outline-secondary" name="submit" value="clear" {{($load_subcart->count() == 0) ? 'disabled' : ''}}>Reset/Clear</button></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse($load_subcart as $c)
                            <div class="d-flex justify-content-between">
                                <div>{{$c->pharmacysub->pharmacysupplymaster->name}}</div>
                                <div>
                                    {{$c->qty_to_process}} {{Str::plural($c->type_to_process, $c->qty_to_process)}}
                                    <button type="submit" name="delete" value="{{$c->id}}" class="btn btn-danger">X</button>
                                </div>
                            </div>
                            @if(!($loop->last))
                            <hr>
                            @endif
                            
                            @empty
                            <h6 class="text-center">Cart is still empty.</h6>
                            @endforelse
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" name="submit" value="process" class="btn btn-success" {{($load_subcart->count() == 0) ? 'disabled' : ''}}>Process</button>
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

        $(document).ready(function () {
            $("#myForm").submit(function (event) {
                var medsValue = $("#meds").val();
                var altMedsValue = $("#alt_meds_id").val();

                // Check if either field is empty
                if (medsValue === "" && altMedsValue === null) {
                    // Prevent the form from submitting
                    event.preventDefault();
                    alert("Please scan or manually input the item to issue before proceeding.");
                }
            });
        });

        $('#type_to_process').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'PIECE') {
                $('#qty_span').text('(in Pieces)');
            }
            else {
                $('#qty_span').text('(in Boxes)');
            }
        }).trigger('change');
    </script>
@endsection