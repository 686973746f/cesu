@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="" method="POST">
            <div class="card">
                <div class="card-header">Bulk Update CIFs</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Date Released</th>
                                <th>Time Released</th>
                                <th>Update Status</th>
                                <th>Update Disposition</th>
                                <th>Update Outcome</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row"></td>
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
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection