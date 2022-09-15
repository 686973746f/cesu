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
                        <div id="showOverride" class="d-none">
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
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-light">
                        <tr>
                            <th style="vertical-align: middle;">#</th>
                            <th style="vertical-align: middle;">Type</th>
                            <th style="vertical-align: middle;">Number of Patients</th>
                            <th style="vertical-align: middle;">Date Created</th>
                            <th style="vertical-align: middle;">Created By</th>
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
                            <td scope="row" style="vertical-align: middle;">{{$item->id}}</td>
                            <td style="vertical-align: middle;">{{($item->type == 1) ? 'ONI' : 'LASALLE'}}</td>
                            <td style="vertical-align: middle;">{{$item->linelistsub->where('linelist_masters_id', $item->id)->count()}}</td>
                            <td style="vertical-align: middle;">{{date('m/d/Y h:i A (D)', strtotime($item->created_at))}}</td>
                            <td style="vertical-align: middle;">{{$item->user->name}}</td>
                            <td class="text-center" style="vertical-align: middle;"><a class="btn btn-primary" href="linelist/{{$link}}/print/{{$item->id}}?s=a4"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}. <a href="{{route('linelist.index')}}">GO BACK</a>
                </div>
                @if($list->count())
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="vertical-align: middle;">Name</th>
                            <th class="text-center" style="vertical-align: middle;">Specimen Location</th>
                            <th class="text-center" style="vertical-align: middle;">Linelist Date Created</th>
                            <th class="text-center" style="vertical-align: middle;">Specimen Date Collected</th>
                            <th class="text-center" style="vertical-align: middle;">Result Released</th>
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
                            <td style="vertical-align: middle;">{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->oniSpecType)) ? 'ONI' : 'LASALLE'}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y (D)', strtotime($item->created_at))}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y (D)', strtotime($item->dateAndTimeCollected))}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{($item->res_released == 1) ? 'Y '.$item->ricon() : 'N'}}</td>
                            <td class="text-center" style="vertical-align: middle;"><a class="btn btn-primary" href="{{route('linelist.print', ['link' => $link, 'id' => $item->id])}}?s=a4"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</a></td>
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
            $('#showOverride').removeClass('d-none');
        }
        else {
            $('#showOverride').addClass('d-none');
        }
    }).trigger('change');
</script>
@endsection
