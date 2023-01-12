@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>View Bakuna Records of {{$p->getName()}} (#{{$p->id}})</div>
                <div>Total Count: {{$list->total()}}</div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="bg-light">
                    <tr>
                        <th>Case Date</th>
                        <th>Case ID</th>
                        <th>Is Booster</th>
                        <th>Animal Type</th>
                        <th>Body Site</th>
                        <th>Category Level</th>
                        <th>Vaccine Brand</th>
                        <th>Outcome</th>
                        <th>Encoded At / By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $b)
                    <tr>
                        <td>{{date('m/d/Y', strtotime($b->case_date))}}</td>
                        <td><a href="{{route('encode_edit', ['br_id' => $b->id])}}">{{$b->case_id}}</a></td>
                        <td>{{($b->is_booster == 1) ? 'Y' : 'N'}}</td>
                        <td>{{$b->animal_type}}</td>
                        <td>{{$b->body_site}}</td>
                        <td>Category {{$b->category_level}}</td>
                        <td>{{$b->brand_name}}</td>
                        <td>{{$b->outcome}}</td>
                        <td>{{date('m/d/Y h:i A', strtotime($b->created_at))}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">No Results Found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>
@endsection