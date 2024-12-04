@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Download Requests</b></div>
                    <div><a href="{{route('export_index')}}" class="btn btn-primary">Refresh</a></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Module</th>
                                <th>Date Created/by</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $d)
                            <tr>
                                <td class="text-center">{{$d->id}}</td>
                                <td>{{$d->name}}</td>
                                <td class="text-center">{{$d->for_module}}</td>
                                <td class="text-center">
                                    <div>{{date('M. d, Y h:i A', strtotime($d->created_at))}}</div>
                                    <div>by {{$d->user->name}}</div>
                                </td>
                                <td class="text-center">
                                    <div>{{mb_strtoupper($d->status)}}</div>
                                    @if($d->status == 'completed')
                                    @php
                                    $createdByDate = Carbon\Carbon::parse($d->created_at);
                                    $finishedDate = Carbon\Carbon::parse($d->date_finished);

                                    // Calculate the difference in minutes
                                    $minutesToFinish = $createdByDate->diffInMinutes($finishedDate);
                                    @endphp

                                    <div><i>Finished on: {{$minutesToFinish}} minutes</i></div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($d->for_module != 'COVID')
                                    <form action="{{route('export_download_file', $d->id)}}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success" {{($d->status == 'pending') ? 'disabled' : ''}}>Download</button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection