@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Case Month</th>
                            <th scope="col">Case Year</th>
                            <th scope="col">Case Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Age</th>
                            <th scope="col">Sex</th>
                            <th scope="col">Brgy</th>
                            <th scope="col">Category</th>
                            <th scope="col">Outcome</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr class="">
                            <td>{{date('F', strtotime($d->case_date))}}</td>
                            <td>{{date('Y', strtotime($d->case_date))}}</td>
                            <td>{{date('m/d/Y', strtotime($d->case_date))}}</td>
                            <td>{{$d->patient->getName()}}</td>
                            <td>{{$d->patient->getAge()}}</td>
                            <td>{{$d->patient->sg()}}</td>
                            <td>{{$d->patient->address_brgy_text}}</td>
                            <td>{{$d->category_level}}</td>
                            <td>{{$d->outcome}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection