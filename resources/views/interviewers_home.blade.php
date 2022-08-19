@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Interviewers ({{number_format($list->total())}})</div>
                    <div>
                        <a href="{{route('interviewers.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('interviewers.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Name / ID" required>
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(session('status'))
                    <div class="alert alert-{{session('statustype')}}" role="alert">
                        {{session('status')}}
                    </div>
                @endif
                @if(request()->input('q'))
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}.
                </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead class="text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $item)
                        <tr>
                            <td style="vertical-align: middle;" class="text-center" scope="row">{{$list->firstItem() + $key}}</td>
                            <td style="vertical-align: middle;">{{$item->lname.", ".$item->fname." ".$item->mname}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->brgy_id)) ? $item->brgy->brgyName : "N/A"}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{$item->desc}}</td>
                            <td class="text-center" style="vertical-align: middle;"><span class="{{($item->enabled == 1) ? 'text-success' : 'text-danger'}}">{{($item->enabled == 1) ? 'Enabled' : 'Disabled'}}</span></td>
                            <td class="text-center" style="vertical-align: middle;">
                                <a href="interviewers/{{$item->id}}/edit" class="btn btn-primary">Edit</a>
                                <form action="{{route('adminpanel.interviewers.options', ['id' => $item->id])}}" method="POST">
                                    @csrf
                                    @if($item->enabled == 1)
                                    <button type="submit" name="submit" value="toggleStatus" class="btn btn-warning my-3">Disable</button>
                                    @else
                                    <button type="submit" name="submit" value="toggleStatus" class="btn btn-success my-3">Enable</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection