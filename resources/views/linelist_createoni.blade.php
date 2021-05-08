@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="">
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
                        <table class="table table-bordered">
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
                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="specNo[]" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="dateCollected[]" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="time" class="form-control" name="timeCollected[]" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="accessionNo[]" id="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                          <select class="form-control" name="user" id="user">
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                          <select class="form-control" name="oniReferringHospital[]" id="">
                                            <option value="CHO GENERAL TRIAS">CHO GENERAL TRIAS</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="oniReferringHospital[]" id="">
                                              <option value="OPS">OPS</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="remarks[]" id="">
                                              <option value="1ST">1ST</option>
                                              <option value="2ND">2ND</option>
                                              <option value="3RD">2ND</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" class="text-right">
                                        <button class="btn btn-primary">Add</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            alert('budol');
            //var data = {{$list}};

            
        });
    </script>
@endsection
