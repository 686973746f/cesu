@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Select Barangay for Clustering View</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Barangay</th>
                            <th>Number of Active Cases</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td><a href="{{route('clustering_view', ['city' => $item['city_id'], 'brgy' => $item['id']])}}" disabled>{{$item['brgyName']}}</a></td>
                            <td class="text-center">{{$item['active_count']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection