@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>OPD Summary</b></div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center thead-light">
                    <tr>
                        <th rowspan="3">OPD</th>
                        <th colspan="6">
                            <h6>Pedia</h6>
                            <h6><i>(0-19 y.o)</i></h6>
                        </th>
                        <th colspan="6">
                            <h6>Adult</h6>
                            <h6><i>(20-59 y.o)</i></h6>
                        </th>
                        <th colspan="6">
                            <h6>Geriatric</h6>
                            <h6><i>(60 AND ABOVE)</i></h6>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
                    </tr>
                    <tr>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection