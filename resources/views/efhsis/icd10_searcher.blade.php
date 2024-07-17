@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>ICD10 Code Search</b></div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="mainTbl">
                    <thead class="thead-light">
                        <tr>
                            <th>ICD10 Code</th>
                            <th>Description</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $("#mainTbl").dataTable({

        });
    </script>
@endsection