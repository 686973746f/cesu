@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="">
            <div class="card">
                <div class="card-header">
                    Create ONI Line List
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>Ziplock No.</th>
                                <th>Date Collected</th>
                                <th>Time Collected</th>
                                <th>Accession No.</th>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>M.I</th>
                                <th>Referring Hospital</th>
                                <th>Date of Birth</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Type of Specimen</th>
                                <th>Remarks</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="time" class="form-control" name="" id="">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="" id="">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection
