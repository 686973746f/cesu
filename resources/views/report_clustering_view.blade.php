@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-bordered">
            <thead class="text-center bg-light">
                <tr>
                    <th colspan="3">{{$brgy_name}}</th>
                </tr>
                <tr>
                    <th>Street Name</th>
                    <th>Number of Cases Inside</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clustered_forms->unique('records.address_street') as $item)
                @php
                    $clusterctr = $clustered_forms->where('records.address_street', $item->records->address_street)->count();
                @endphp
                <tr>
                    <td scope="row" class="{{($clusterctr >= 2) ? 'bg-danger font-weight-bold text-warning' : ''}}">{{$item->records->address_street}}</td>
                    <td class="text-center {{($clusterctr >= 2) ? 'bg-danger font-weight-bold text-warning' : ''}}"><a href="">{{$clusterctr}}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
@endsection