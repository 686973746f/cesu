@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="{{route('linelist.oni.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header font-weight-bold">
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
                    <table class="table table-bordered" id="tbl">
                        <thead>
                            <tr class="text-center">
                                <th style="vertical-align: middle;">Ziplock No.</th>
                                <th style="vertical-align: middle;">Date Collected</th>
                                <th style="vertical-align: middle;">Time Collected</th>
                                <th style="vertical-align: middle;">Accession No.</th>
                                <th style="vertical-align: middle; width: 500px;">Patient</th>
                                <th style="vertical-align: middle;">Referring Hospital</th>
                                <th style="vertical-align: middle;">Type of Specimen</th>
                                <th style="vertical-align: middle;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="trclone">
                                <td>
                                    <div class="form-group">
                                      <input type="text" class="form-control text-center" name="linelist[0][specNo]" id="specNo" value="1" readonly required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="linelist[0][dateCollected]" id="" value="{{date('Y-m-d')}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="time" class="form-control" name="linelist[0][timeCollected]" id="" value="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="linelist[0][accessionNo]" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                      <select name="linelist[0][user]" class="patient" required>
                                          <option value="" selected disabled>Choose...</option>
                                            @foreach($list as $item)
                                                <option value="{{$item->id}}">{{$item->lname.", ".$item->fname." ".$item->mname}} | {{$item->getAge()}}/{{substr($item->gender, 0, 1)}} | {{date('m/d/Y', strtotime($item->bdate))}}</option>
                                            @endforeach
                                      </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                      <select class="form-control" name="linelist[0][oniReferringHospital]" id="" required>
                                        <option value="CHO GENERAL TRIAS">CHO GENERAL TRIAS</option>
                                      </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" name="linelist[0][oniSpecType]" id="oniSpecType" required>
                                          <option value="OPS">OPS</option>
                                          <option value="NPS">NPS</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" name="linelist[0][remarks]" id="" required>
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
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-success" onclick="return confirm('This will now process the linelist. Click OK to proceed.')" type="submit"><i class="fas fa-save mr-2"></i>Save</button>
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

       $('#remove').prop('disabled', true);

       $('#remove').click(function (e) { 
           e.preventDefault();
           n--;
           $('#tbl tr:last').remove();
           
           if(n == 1) {
               $('#remove').prop('disabled', true);
           }
       });

       $('#add').click(function (e) {
            e.preventDefault();
            for(i=1; i <= $('#rowsToAdd').val(); i++) {
                n++;
            
                $('.patient').each(function(){ // do this for every select with the 'combobox' class
                    if ($(this)[0].selectize) { // requires [0] to select the proper object
                        var value = $(this).val(); // store the current value of the select/input
                        $(this)[0].selectize.destroy(); // destroys selectize()
                        $(this).val(value);  // set back the value of the select/input
                    }
                });

                var clone = $(newRowContent).clone();
                var prefix = "linelist[" + (n-1) + "]";
                $(clone).find('#specNo').val(n);
                $(clone).find("input").each(function() {
                    this.name = this.name.replace(/linelist\[\d+\]/, prefix);
                });
                $(clone).find("select").each(function() {
                    this.name = this.name.replace(/linelist\[\d+\]/, prefix);
                });
                $(clone).find('#oniSpecType').val($('#oniSpecType').val());
                $(clone).appendTo($('#tbl tbody'));
                $('.patient').selectize();
            }

            if(n != 1) {
                $('#remove').prop('disabled', false);
            }
            
            $('#rowsToAdd').val(1);
       });
    </script>
@endsection 
