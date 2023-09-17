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
                                <th>Created At / By</th>
                                <th>Updated At / By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $i)
                            <tr>
                                <td class="text-center"><b>{{$list->firstItem() + $k}}</b></td>
                                <td><a href="{{route('pharmacy_view_branch', $i->id)}}"><b>{{$i->name}}</b></a></td>
                                <td class="text-center">{{($i->focal_person) ? $i->focal_person : 'N/A'}}</td>
                                <td class="text-center">{{($i->contact_number) ? $i->contact_number : 'N/A'}}</td>
                                <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($i->created_at))}} / {{$i->user->name}}</small></td>
                                <td class="text-center"><small>{{($i->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($i->updated_at)).' / '.$i->getUpdatedBy->name : 'N/A'}}</small></td>
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

    <form action="{{route('pharmacy_store_branch')}}" method="POST">
        @csrf
        <div class="modal fade" id="addBranch" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Branch</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="name"><b class="text-danger">*</b>Name</label>
                          <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group">
                          <label for="name">Focal Person</label>
                          <input type="text" class="form-control" name="focal_person" id="focal_person" style="text-transform: uppercase;" value="{{old('focal_person')}}">
                        </div>
                        <div class="form-group">
                          <label for="contact_number">Contact Number</label>
                          <input type="text" class="form-control" name="contact_number" id="contact_number" pattern="[0-9]{11}" value="{{old('contact_number')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" style="text-transform: uppercase;" value="{{old('description')}}">
                          </div>
                        <div class="form-group">
                          <label for="level"><b class="text-danger">*</b>Level</label>
                          <select class="form-control" name="level" id="level" required>
                            <option value="1" {{(old('level') == 1) ? 'selected' : ''}}>1 (Main Entity)</option>
                            <option value="2" {{(old('level') == 2) ? 'selected' : ''}}>2 (Sub Entity)</option>
                          </select>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="if_bhs" id="if_bhs" value="1" {{(old('if_bhs')) ? 'checked' : ''}}>Check if Branch is BHS
                          </label>
                        </div>
                        <div class="form-group d-none mt-2" id="div_bhs">
                          <label for="if_bhs_id"><b class="text-danger">*</b>Select Barangay ID to link in BHS</label>
                          <select class="form-control" name="if_bhs_id" id="if_bhs_id">
                            <option value="" disabled {{!(old('if_bhs_id')) ? 'selected' : ''}}>Choose...</option>
                            @foreach($list_brgy as $b)
                            <option value="{{$b->id}}" {{(old('if_bhs_id') == $b->id) ? 'selected' : ''}}>{{$b->name}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#if_bhs').change(function (e) { 
            e.preventDefault();
            if ($(this).prop("checked")) {
                $('#div_bhs').removeClass('d-none');
                $('#if_bhs_id').prop('required', true);
            } else {
                $('#div_bhs').addClass('d-none');
                $('#if_bhs_id').prop('required', false);
            }
        }).trigger('change');

        $('#if_bhs_id').select2({
            theme: 'bootstrap',
        });
    </script>
@endsection