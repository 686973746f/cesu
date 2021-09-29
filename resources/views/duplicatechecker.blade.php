@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr>
                    <td scope="row">{{$record['id']}}</td>
                    <td>{{$record['lname'].', '.$record['fname'].' '.$record['mname']}}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection