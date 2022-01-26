@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Self-Report List<a href="{{route('selfreport.view')}}" class="btn btn-link {{(request()->get('viewCompleted') == 'true') ? '' : 'disabled'}}">View Pending</a><a href="{{route('selfreport.view')}}?viewCompleted=true" class="btn btn-link {{(request()->get('viewCompleted') == 'true') ? 'disabled' : ''}}">View Completed</a>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>{{(request()->get('viewCompleted') == 'true') ? 'Viewing COMPLETED Records only. ' : 'Viewing PENDING Records only. '}}Sorted by first come first serve basis (Oldest to Newest).
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="sr_table">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Date Submitted</th>
                                <th>Name/Request #</th>
                                <th>Age/Gender</th>
                                <th>Contact #</th>
                                <th>Street</th>
                                <th>Brgy</th>
                                <th>City/Province</th>
                                <th>Patient Type</th>
                                <th>Date Swabbed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $item)
                            <tr>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($item->created_at))}}</td>
                                <td><a href="{{route('selfreport.edit', ['id' => $item->id])}}" class="btn btn-link">{{$item->getName()}} <small>(#{{$item->id}})</small></a></td>
                                <td class="text-center">{{$item->getAge()}} / {{substr($item->gender,0,1)}}</td>
                                <td class="text-center">{{$item->mobile}}</td>
                                <td><small>{{$item->address_street}}</small></td>
                                <td class="text-center">{{$item->address_brgy}}</td>
                                <td class="text-center">{{$item->address_city.', '.$item->address_province}}</td>
                                <td class="text-center">{{$item->getType()}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($item->testDateCollected1))}} <small>({{$item->diff4Humans($item->testDateCollected1)}})</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#sr_table').DataTable();
    </script>
@endsection