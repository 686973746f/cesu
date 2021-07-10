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
                <form action="{{route('paswab.view')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search">
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(request()->input('q'))
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}. <a href="{{route('paswab.view')}}">GO BACK</a>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered" id="paswabtbl">
                        <thead class="text-center bg-light">
                            <tr>
                                <th>Date Submitted</th>
                                <th>Referral Code</th>
                                <th>Schedule Code</th>
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
                            @foreach ($list as $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;"><small>{{date('m/d/Y h:i:s A', strtotime($item->created_at))}}</small></td>
                                    <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->linkCode)) ? $item->linkCode : 'N/A'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->majikCode}}</td>
                                    <td style="vertical-align: middle;"><a href="/forms/paswab/view/{{$item->id}}" class="btn btn-link text-left">{{$item->getName()}}</a></td>
                                    <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->philhealth)) ? $item->philhealth : 'N/A'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->bdate))}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->getAge()." / ".$item->gender}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->pType}} <small>{{(!is_null($item->expoDateLastCont) && $item->pType == 'CLOSE CONTACT') ? "(".date('m/d/Y', strtotime($item->expoDateLastCont)).")" : ''}}</small></td>
                                    <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->interviewDate))}}</td>
                                    <td style="vertical-align: middle;"><small>{{$item->getAddress()}}</small></td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->mobile}}</td>
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

    <script>
        $('#paswabtbl').dataTable({
            dom: 'tr',
            "order": [0, 'asc']
        });
    </script>
@endsection