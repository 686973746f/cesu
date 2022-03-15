@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">List of Confirmed Patient Clustering in {{$subd}}, BRGY. {{$brgy_data->brgyName}}, {{$city_data->cityName}}, {{$city_data->province->provinceName}} - Total: {{$list->count()}}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Address</th>
                            <th>Street</th>
                            <th>Brgy</th>
                            <th>City/Province</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td><a href="{{route('records.edit', ['record' => $item->records->id])}}">{{$item->records->getName()}}</a></td>
                            <td class="text-center">{{$item->records->getAgeInt()}} / {{substr($item->records->gender,0,1)}}</td>
                            <td class="text-center"><small>{{$item->records->address_houseno}}</small></td>
                            <td class="text-center">{{$item->records->address_street}}</td>
                            <td class="text-center">{{$item->records->address_brgy}}</td>
                            <td class="text-center">{{$item->records->address_city}}, {{$item->records->address_province}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection