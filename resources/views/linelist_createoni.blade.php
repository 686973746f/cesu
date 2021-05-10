@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="{{route('linelist.oni.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    Create ONI Line List
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="dru">Name of Institute/Facility</label>
                              <input type="text" class="form-control" name="dru" id="dru" value="{{old('dru', 'CHO GENERAL TRIAS')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contactPerson">Name of Contact Person</label>
                                <input type="text" class="form-control" name="contactPerson" id="contactPerson" value="{{old('contactPerson', 'LUIS BROAS')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contactMobile">Contact Number</label>
                                <input type="text" class="form-control" name="contactMobile" id="contactMobile" value="{{old('contactMobile', '09175611254')}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl">
                            <thead>
                                <tr class="text-center">
                                    <th>Ziplock No.</th>
                                    <th>Date Collected</th>
                                    <th>Time Collected</th>
                                    <th>Accession No.</th>
                                    <th>Patient</th>
                                    <th>Referring Hospital</th>
                                    <th>Type of Specimen</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="trclone">
                                    <td>
                                        <div class="form-group">
                                          <input type="text" class="form-control text-center" name="specNo[]" id="specNo" value="1" readonly required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="dateCollected[]" id="" value="{{date('Y-m-d')}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="time" class="form-control" name="timeCollected[]" id="" value="14:00" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="accessionNo[]" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                          <select class="form-control" name="user[]" id="user" required>
                                              <option value="">Choose...</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                          <select class="form-control" name="oniReferringHospital[]" id="" required>
                                            <option value="CHO GENERAL TRIAS">CHO GENERAL TRIAS</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="oniSpecType[]" id="" required>
                                              <option value="OPS">OPS</option>
                                              <option value="NPS">NPS</option>
                                            </select>
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
                            <tfoot>
                                <tr>
                                    <td colspan="8" class="text-right">
                                        <button class="btn btn-primary" id="add"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
       $.ajax({
            type: "GET",
            url: "/ajaxGetLineList/",
            data: "data",
            dataType: "json",
            cache: false,
            processData: false,
            error: function(xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    alert(err.Message);
            },
            success: function (response) {
                $.each(response['data'], function (indexInArray, valueOfElement) {
                    $('#user').append('<option value="' + response['data'][indexInArray].id + '">'+response['data'][indexInArray].lname + ', ' + response['data'][indexInArray].fname + ' ' + response['data'][indexInArray].mname + ' | ' + response['data'][indexInArray].bdate + ' | ' + response['data'][indexInArray].gender + '</option>'); 
                });
            }
       });

       var newRowContent = $('.trclone');
       var n = 1;

       $('#add').click(function (e) { 
            n++;
            e.preventDefault();
            var clone = $(newRowContent).clone();
            $(clone).find('#specNo').val(n);
            //var id_num = 'specNo'+n;  
            //$('#specNo'+n).val(n);
            $(clone).appendTo($('#tbl tbody'));
       });
    </script>
@endsection 
