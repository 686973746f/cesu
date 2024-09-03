@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Download Requests</div>
            <div class="card-body">
                <div>
                    <table class="table table-bordered ">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>For Module</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $d)
                            <tr>
                                <td class="text-center">{{$d->id}}</td>
                                <td>{{$d->name}}</td>
                                <td class="text-center">{{$d->for_module}}</td>
                                <td class="text-center">{{$d->status}}</td>
                                <td class="text-center">{{date('M. d, Y h:i A', strtotime($d->created_at))}}</td>
                                <td class="text-center">
                                    <form action="{{route('export_download_file', $d->id)}}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" {{($d->status == 'pending') ? 'disabled' : ''}}>Download</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection