@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header font-weight-bold">Clustering Count on BRGY. {{$brgy_data->brgyName}}, {{$brgy_data->city->cityName}}, {{$brgy_data->city->province->provinceName}}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-light">
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
                                <td class="text-center {{($clusterctr >= 2) ? 'bg-danger font-weight-bold text-warning' : ''}}"><a href="{{route('clustering_viewlist', ['city' => $city_data->id, 'brgy' => $brgy_data->id, 'subd' => $item->records->address_street])}}">{{$clusterctr}}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection