@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Select Barangay for Clustering View</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td><a href="{{route('clustering_view', ['city' => $item->city_id, 'brgy' => $item->id])}}">{{$item->brgyName}}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection