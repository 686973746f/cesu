@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>List of Branches</b> (Total: {{$list->total()}})</div>
                    <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBranch">Add Branch</button></div>
                </div>
            </div>

            
            
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search By Branch Name | ID" style="text-transform: uppercase;" autocomplete="off" required>
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Focal Person</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $i)
                            <tr>
                                <td class="text-center">{{$list->firstItem() + $k}}</td>
                                <td><a href="{{route('pharmacy_view_branch', $i->id)}}">{{$i->name}}</a></td>
                                <td class="text-center">{{($i->focal_person) ? $i->focal_person : 'N/A'}}</td>
                                <td class="text-center">{{($i->contact_number) ? $i->contact_number : 'N/A'}}</td>
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

    <form action="" method="POST">
        @csrf
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Branch</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        Body
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection