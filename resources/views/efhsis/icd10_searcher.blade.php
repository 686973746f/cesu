@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>ICD10 Code Search</b></div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" style="text-transform: uppercase;" autocomplete="off" minlength="3" required>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if(request()->input('q'))
                <table class="table table-striped table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ICD10 Code</th>
                            <th>Description</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center"><b>{{$d->ICD10_CODE}}</b></td>
                            <td>{{$d->ICD10_DESC}}</td>
                            <td class="text-center">{{$d->ICD10_CAT}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
                @else
                <p class="text-center">Type in the search field first to proceed.</p>
                @endif
            </div>
        </div>
    </div>
@endsection