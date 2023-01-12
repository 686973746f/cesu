@extends('layouts.app')

@section('content')
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