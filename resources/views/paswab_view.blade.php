@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">Pa-Swab List</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>Date Submitted</th>
                                <th>Name</th>
                                <th>Philhealth</th>
                                <th>Birthdate</th>
                                <th>Age / Gender</th>
                                <th>Client Type</th>
                                <th>Date Interviewed</th>
                                <th>Address</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list as $item)
                                <tr>
                                    <td class="text-center">{{date('m/d/Y h:i:s A', strtotime($item->created_at))}}</td>
                                    <td><a href="/forms/paswab/view/{{$item->id}}">{{$item->getName()}}</a></td>
                                    <td class="text-center">{{(!is_null($item->philhealth)) ? $item->philhealth : 'N/A'}}</td>
                                    <td class="text-center">{{date('m/d/Y', strtotime($item->bdate))}}</td>
                                    <td class="text-center">{{$item->getAge()." / ".$item->gender}}</td>
                                    <td class="text-center">{{$item->pType}} <small>{{(!is_null($item->pType) && $item->pType == 'CLOSE CONTACT') ? "(".date('m/d/Y', strtotime($item->expoDateLastCont)).")" : ''}}</small></td>
                                    <td class="text-center">{{date('m/d/Y', strtotime($item->interviewDate))}}</td>
                                    <td><small>{{$item->getAddress()}}</small></td>
                                    <td class="text-center">{{$item->mobile}}</td>
                                </tr>
                            @empty
                                empty
                            @endforelse
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