@extends('layouts.app')

@section('content')

@if($prescription)
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="{{route('pharmacy_patient_addcart', $d->id)}}" method="POST" id="myForm">
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
                                          <div><b><a href="{{route('pharmacy_view_patient', $d->id)}}">{{$d->getName()}} <small>(#{{$d->id}})</small></a></b></div>
                                        </td>
                                        <td>
                                          <div><b>BIRTHDATE:</b></div>
                                          <div>{{date('m/d/Y', strtotime($d->bdate))}}</div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div><b>AGE/SEX:</b></div>
                                            <div>{{$d->getAge()}} / {{$d->sg()}}</div>
                                        </td>
                                        <td>
                                          <div><b>BARANGAY:</b></div>
                                          <div>{{$d->address_brgy_text}}</div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div><b>PRESCRIPTION ID / DATE:</b></div>
                                            <div><a href="{{route('pharmacy_view_prescription', $prescription->id)}}">#{{$prescription->id}} - {{date('m/d/Y', strtotime($prescription->created_at))}}</a></div>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <button type="button" class="btn btn-success ml-2" id="new_prescription_btn">New Prescription</button>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="2">
                                            <div><b>REQUESTING MEDS FOR:</b></div>
                                            <div>{{$prescription->concerns_list}}</div>
                                        </td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="selected_maincart_id" value="{{$load_cart->id}}">
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
                                        <option value="PIECE" {{(old('type_to_process') == 'PIECE') ? 'selected' : ''}}>Piece</option>
                                        <!--<option value="BOX">Box</option>-->
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="qty_to_process"><b class="text-danger">*</b>Quantity <span id="qty_span"></span></label>
                                        <input type="text" class="form-control" name="qty_to_process" id="qty_to_process" min="1" max="999" value="{{old('qty_to_process')}}" required>
                                    </div>
                                </div>
                            </div>
                            <!--
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="enable_override" id="enable_override" value="checkedValue"> Enable Override <i>(Ignore Quantity and Duration Limit)</i>
                                    </label>
                                </div>
                            -->
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block" name="submit" value="add_cart">Add to Cart</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{route('pharmacy_patient_process_cart', $d->id)}}" method="POST">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div><b>Cart</b> ({{$load_subcart->count()}})</div>
                                <div><button type="button" class="btn btn-outline-secondary" id="resetFakeBtn" {{($load_subcart->count() == 0) ? 'disabled' : ''}}>Reset/Clear</button></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="selected_maincart_id" value="{{$load_cart->id}}">
                            @if($load_subcart->count())
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ITEM</th>
                                        <th>QTY TO ISSUE</th>
                                        <th>MAX QTY LIMIT (BASED ON RX)</th>
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

                @if($scard->count() != 0)
                <div id="accordianId" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab" id="section1HeaderId">
                            <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">▼ Show Previous Transaction (from the last 30 Days) - Click to View</a>
                        </div>
                        <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date/Time</th>
                                                <th>Medicine</th>
                                                <th>Quantity</th>
                                                <th>Encoder</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('pharmacy_patient_addcart', $d->id)}}" method="POST" class="d-none">
        @csrf
        <button type="submit" class="btn btn-primary" id="new_prescription_hdn" name="submit" value="new_prescription"></button>
    </form>

    <form action="{{route('pharmacy_patient_process_cart', $d->id)}}" method="POST" class="d-none">
        @csrf
        <button type="submit" class="btn btn-outline-secondary" name="submit" value="clear" id="resetRealBtn"></button>
    </form>

    <script>
        $('#alt_meds_id').select2({
            theme: 'bootstrap',
        });
        

        $(document).ready(function () {
            @if($scard->count() != 0)
            $('#accordianId').on('show.bs.collapse', function () {
                // Accordion is being opened, change the text
                $('#section1HeaderId a').text('▲ Hide Previous Transaction/s (from the last 30 Days) - Click to Hide');
            });

            $('#accordianId').on('hide.bs.collapse', function () {
                // Accordion is being closed, change the text back
                $('#section1HeaderId a').text('▼ Show Previous Transaction/s (from the last 30 Days) - Click to View');
            });
            @endif
            
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

            $('#new_prescription_btn').click(function (e) { 
                e.preventDefault();
                
                var result = confirm("Current Prescription will be marked as Finished. Continue?");

                if (result) {
                    $('#new_prescription_hdn').click();
                }
            });

            $('#resetFakeBtn').click(function (e) { 
                e.preventDefault();

                var result = confirm("This will clear the items listed on the Cart of the Patient, continue?");

                if (result) {
                    $('#resetRealBtn').click();
                }
            });

            $('.deleteButton').click(function (e) { 
                $('.pcslimit').prop('required', false);
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
@else
<div class="container">
    <form action="{{route('pharmacy_patient_addcart', $d->id)}}" method="POST" id="myForm">
        @csrf
        <div class="card">
            <div class="card-header"><b>Initialize Patient Record</b></div>
            <div class="card-body">
                <div class="alert alert-info text-center" role="alert">
                    @if(!is_null($d->itr_id))
                    <b>Patient was encoded from OPD.</b> Please fill-out the fields below before the patient can request medicines.
                    @else
                    Please fill-out the criteria below before the patient can request medicines.
                    @endif
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td class="bg-light">Name of Patient / ID</td>
                        <td class="text-center"><b><a href="{{route('pharmacy_view_patient', $d->id)}}">{{$d->getName()}} <small>(#{{$d->id}})</small></a></b></td>
                        <td class="bg-light">Age / Sex</td>
                        <td class="text-center">{{$d->getAge()}} / {{$d->sg()}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Birthdate</td>
                        <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}}</td>
                        <td class="bg-light">Barangay</td>
                        <td class="text-center">{{$d->address_brgy_text}}</td>
                    </tr>
                    @if(!is_null($d->itr_id))
                    <tr>
                        <td class="bg-light">Date of Consultation</td>
                        <td class="text-center">{{date('m/d/Y', strtotime($d->getLatestItr()->consultation_date))}}</td>
                        <td class="bg-light">Chief Complain</td>
                        <td class="text-center"><b>{{$d->getLatestItr()->chief_complain}}</b></td>
                    </tr>
                    <tr>
                        <td class="bg-light">Diagnosis</td>
                        <td class="text-center" colspan="3">{{$d->getLatestItr()->dcnote_assessment}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">RX</td>
                        <td class="text-center" colspan="3">{{$d->getLatestItr()->dcnote_plan}}</td>
                    </tr>
                    @endif
                    @if($d->from_outside == 1)
                    <tr>
                        <td class="bg-light">Name of Hospital/Clinic</td>
                        <td class="text-center" colspan="3">{{$d->outside_name}}</td>
                    </tr>
                    @endif
                </table>
                <hr>
                @if(is_null($d->is_lgustaff))
                <div class="form-group">
                    <label for="is_lgustaff"><b class="text-danger">*</b>Is the Patient a Staff from LGU?</label>
                    <select class="form-control" name="is_lgustaff" id="is_lgustaff" required>
                        <option value="" {{(is_null(old('is_lgustaff'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('is_lgustaff') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('is_lgustaff') == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                @endif
                <div class="form-group d-none" id="if_lgustaff">
                  <label for="lgu_office_name">Name of LGU Office <i>(Optional)</i></label>
                  <input type="text" class="form-control" name="lgu_office_name" id="lgu_office_name" value="{{old('lgu_office_name')}}" style="text-transform: uppercase;">
                </div>
                <div class="form-group">
                    <label for="concerns_list"><span class="text-danger font-weight-bold">*</span>Requesting Medicine/s for <i>(Select all that apply)</i></label>
                    <select class="form-control" name="concerns_list[]" id="concerns_list" multiple required>
                        @foreach($getReasonList as $rea)
                        <option value="{{$rea}}">{{$rea}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" name="submit" value="submit_changes">Submit Changes</button>
            </div>
        </div>
    </form>
</div>

<script>
    $('#concerns_list').select2({
        theme: 'bootstrap',
    });

    $('#is_lgustaff').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#if_lgustaff').removeClass('d-none');
        }
        else {
            $('#if_lgustaff').addClass('d-none');
        }
    });
</script>
@endif
@endsection