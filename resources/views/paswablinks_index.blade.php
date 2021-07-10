@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>Pa-Swab Links</div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addlink">Add Link</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                @if($data->count())
                <table class="table table-bordered">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>URL</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td style="vertical-align: middle;" scope="row" class="text-center">{{$item->id}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{$item->code}}</td>
                            <td style="vertical-align: middle;" class="text-center font-weight-bold text-{{($item->active == 1) ? 'success' : 'danger'}}">{{($item->active == 1) ? 'Enabled' : 'Disabled'}}</td>
                            <td style="vertical-align: middle;" class="text-center"><a href="https://paswab.cesugentri.com/?rlink={{$item->code}}">https://paswab.cesugentri.com/?rlink={{$item->code}}&s={{$item->secondary_code}}</a></td>
                            <td style="vertical-align: middle;" class="text-center">{{date('m/d/Y h:i A', strtotime($item->created_at))}}</td>
                            <td style="vertical-align: middle;" class="text-center">
                                <form action="/admin/paswablinks/{{$item->id}}/options" method="POST">
                                    @csrf
                                    @if($item->active == 1)
                                    <button type="submit" name="submit" value="activeInit" class="btn btn-warning">Disable</button>
                                    @else
                                    <button type="submit" name="submit" value="activeInit" class="btn btn-success">Enable</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center">No data available in table.</p>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('paswablinks.store')}}" method="POST" autocomplete="off">
        @csrf
        <div class="modal fade" id="addlink" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="code">Input Pa-swab Referral Link here:</label>
                          <input type="text" class="form-control" name="code" id="code" value="{{old('code')}}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection