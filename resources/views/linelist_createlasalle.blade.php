@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="{{route('linelist.lasalle.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Create LaSalle Linelist</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="dru">Disease Reporting Unit (Hospital/Agency)</label>
                              <input type="text" name="dru" id="dru" class="form-control" value="CITY HEALTH OFFICE - GENERAL TRIAS" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="laSallePhysician">Referring Physician</label>
                                <input type="text" name="laSallePhysician" id="laSallePhysician" class="form-control" value="Dr. JONATHAN P. LUSECO" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="contactPerson">Contact Person</label>
                              <input type="text" name="contactPerson" id="contactPerson" class="form-control" value="LUIS P. BROAS" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Official E-mail Address</label>
                                <input type="email" name="email" id="email" class="form-control" value="cesu.gentrias@gmail.com" required>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="contactTelephone">Contact Person</label>
                              <input type="text" name="contactTelephone" id="contactTelephone" class="form-control" value="(046) 509 5289" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactMobile">Mobile Number</label>
                                <input type="text" name="contactMobile" id="contactMobile" class="form-control" value="0917 561 1254" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shipmentDate">Date of Specimen Shipment</label>
                                <input type="date" name="shipmentDate" id="shipmentDate" class="form-control" value="{{date('Y-m-d', strtotime('tomorrow'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shipmentTime">Time of Specimen Shipment</label>
                                <input type="time" name="shipmentTime" id="shipmentTime" class="form-control" value="10:00" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-bordered" id="tbl">
                        <thead>
                            <tr class="text-center">
                                <th style="vertical-align: middle; width: 150px;">No.</th>
                                <th style="vertical-align: middle; width: 800px;">Patient</th>
                                <th style="vertical-align: middle;">Date of Specimen Collection</th>
                                <th style="vertical-align: middle;">Time of Specimen Collection</th>
                                <th style="vertical-align: middle; width: 200px;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="trclone">
                                <td scope="row">
                                    <div class="form-group">
                                        <input type="text" class="form-control text-center" name="specNo[]" id="specNo" value="1" readonly required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="user[]" class="patient" required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($list as $item)
                                            <option value="{{$item->id}}">{{$item->lname.", ".$item->fname." ".$item->mname}} | {{$item->getAge()}}/{{substr($item->gender, 0, 1)}} | {{date('m/d/Y', strtotime($item->bdate))}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="dateCollected[]" id="" value="{{date('Y-m-d')}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="time" class="form-control" name="timeCollected[]" id="timeCollected" value="14:00" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" name="remarks[]" id="" required>
                                          <option value="1ST">1ST</option>
                                          <option value="2ND">2ND</option>
                                          <option value="3RD">3RD</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-inline" style="display: flex; justify-content: flex-end">
                        <button class="btn btn-danger mx-2" id="remove"><i class="fas fa-minus-circle mr-2"></i>Remove</button>
                        <input class="form-control mx-2" type="number" min="1" max="1000" name="rowsToAdd" id="rowsToAdd" value="1">
                        <button class="btn btn-primary mx-2" id="add"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add</button>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="laSallePreparedBy">Prepared by</label>
                                <input type="text" class="form-control" name="laSallePreparedBy" id="laSallePreparedBy" value="DAISY A. ROJAS" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="laSallePreparedByDate">Date</label>
                                <input type="date" class="form-control" name="laSallePreparedByDate" id="laSallePreparedByDate" value="{{date('Y-m-d', strtotime('tomorrow'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="laSallePreparedByTime">Time</label>
                                <input type="time" class="form-control" name="laSallePreparedByTime" id="laSallePreparedByTime" value="10:00" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" onclick="return confirm('This will now process the linelist. Click OK to proceed.')" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('.patient').selectize();
        });

       var newRowContent = $('.trclone');
       var n = 1;
       var tens = 1;
       var hour = 14; //starts at 2 PM
       var min = 0;
       var minstr = '';

       $('#remove').prop('disabled', true);

       $('#remove').click(function (e) { 
           e.preventDefault();
           n--;
           tens = n;
           $('#tbl tr:last').remove();
           
           if(n == 1) {
               $('#remove').prop('disabled', true);
           }

           min = min - 2;
       });

       $('#add').click(function (e) { 
            e.preventDefault();
            for(i=1; i <= $('#rowsToAdd').val(); i++) {
                n++;
                tens++;

                min = min + 2;

                if(min == 60) {
                    min = 0;
                    hour = hour + 1;
                }

                if(min <= 9) {
                    minstr = '0'+min;
                }
                else {
                    minstr = min;
                }

                $('.patient').each(function(){ // do this for every select with the 'combobox' class
                    if ($(this)[0].selectize) { // requires [0] to select the proper object
                        var value = $(this).val(); // store the current value of the select/input
                        $(this)[0].selectize.destroy(); // destroys selectize()
                        $(this).val(value);  // set back the value of the select/input
                    }
                });
                
                var clone = $(newRowContent).clone();
                $(clone).find('#specNo').val(tens);
                $(clone).find('#timeCollected').val(hour+ ':' + minstr);
                $(clone).appendTo($('#tbl tbody'));
                $('.patient').selectize();
                
                if(tens == 10) {
                    tens = 0;
                }
            }
            
            if(n != 1) {
                $('#remove').prop('disabled', false);
            }

            $('#rowsToAdd').val(1);
       });
    </script>
@endsection