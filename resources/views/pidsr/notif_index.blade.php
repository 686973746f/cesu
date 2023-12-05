@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>PIDSR Notifications</b></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Date/Time</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notif_list as $d)
                            <tr class="text-center">
                                <td class="{{($d->ifRead()) ? '' : 'font-weight-bold'}}">{{date('m/d/Y H:i A', strtotime($d->created_at))}}</td>
                                <td class="{{($d->ifRead()) ? '' : 'font-weight-bold'}}">{{$d->disease}} {{$d->message}}</td>
                                <td><a href="{{route('pidsr_notif_view', $d->id)}}" class="btn btn-primary">View</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{$notif_list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection