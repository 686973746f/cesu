@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="font-weight-bold">
                    Line List
                </div>
                <div>
                    <form action="{{route('linelist.create')}}" method="POST">
                        @csrf
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="isOverride" id="isOverride" value="1">
                            Override Mode <i>(Only check IF for processing late/reject records)</i>
                          </label>
                        </div>
                        <div id="showOverride">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="sFrom">Get Records Starting From</label>
                                      <input type="date" class="form-control" name="sFrom" id="sFrom" min="{{date('Y-m-d', strtotime("-3 Months"))}}" value="{{date('Y-m-d', strtotime("yesterday"))}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sTo">And Ending To</label>
                                        <input type="date" class="form-control" name="sTo" id="sTo" min="{{date('Y-m-d', strtotime("-3 Months"))}}" value="{{date('Y-m-d')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-right">
                            <button class="btn btn-success" name="submit" value="1">Create LaSalle</button>
                            <button class="btn btn-success" name="submit" value="2">Create ONI</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-{{session('statustype')}}" role="alert">
                    {{session('status')}}
                </div>
                <hr>
            @endif

            <form action="{{route('linelist.index')}}" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Name of Patient if Inside Linelists">
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                @if(!request()->input('q'))
                <table class="table table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Number of Patients</th>
                            <th>Date Created</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $item)
                        @php
                        if($item->type == 1) {
                            $link = 'oni';
                        }
                        else {
                            $link = 'lasalle';
                        }
                        @endphp
                        <tr>
                            <td scope="row">{{$item->id}}</td>
                            <td>{{($item->type == 1) ? 'ONI' : 'LASALLE'}}</td>
                            <td>{{$item->linelistsub->where('linelist_masters_id', $item->id)->count()}}</td>
                            <td>{{date('m/d/Y h:i A', strtotime($item->created_at))}}</td>
                            <td class="text-center"><a class="btn btn-primary" href="linelist/{{$link}}/print/{{$item->id}}?s=legal">Print (Legal)</a></td>
                            <td class="text-center"><a class="btn btn-primary" href="linelist/{{$link}}/print/{{$item->id}}?s=a4">Print (A4)</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}. <a href="{{route('linelist.index')}}">GO BACK</a>
                </div>
                @if($list->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Specimen Location</th>
                            <th class="text-center">Linelist Date Created</th>
                            <th class="text-center">Specimen Date Collected</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $item)
                        @php
                        if(!is_null($item->oniSpecType)) {
                            $link = 'oni';
                        }
                        else {
                            $link = 'lasalle';
                        }
                        @endphp
                        <tr>
                            <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td class="text-center">{{(!is_null($item->oniSpecType)) ? 'ONI' : 'LASALLE'}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->created_at))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->dateAndTimeCollected))}}</td>
                            <td class="text-center"><a class="btn btn-primary" href="linelist/{{$link}}/print/{{$item->linelist_masters_id}}?s=legal">Print (Legal)</a></td>
                            <td class="text-center"><a class="btn btn-primary" href="linelist/{{$link}}/print/{{$item->linelist_masters_id}}?s=a4">Print (A4)</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endif
            </div>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

<script>
    $('#isOverride').change(function (e) { 
        e.preventDefault();
        if($(this).prop('checked') == true) {
            $('#showOverride').show();
        }
        else {
            $('#showOverride').hide();
        }
    }).trigger('change');
</script>
@endsection
