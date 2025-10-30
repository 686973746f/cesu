@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('sbs_list')}}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Configure Grade Levels and Sections:</b> {{$s->name}}</div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId">Add New Group</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                <hr>
                @endif

                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Grade Level <small>(Click to view Section)</small></th>
                            <th>Number of Sections</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td><a href="{{route('sbs_viewsection', $l->id)}}">{{$l->level_name}}</a></td>
                            <td class="text-center">{{$l->sections()->count()}}</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($l->created_at))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <form action="{{route('sbs_storelevel')}}" method="POST">
        @csrf
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type"><span class="text-danger font-weight-bold">*</span>Link Group to</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="" disabled {{(is_null(old('type'))) ? 'selected' : ''}}>Choose...</option>
                                @if(in_array("ES", explode(", ", $s->school_type)))
                                <option value="ES" {{(old('type') == 'ES') ? 'selected' : ''}}>Elementary School</option>
                                @endif
                                @if(in_array("JHS", explode(", ", $s->school_type)))
                                <option value="JHS" {{(old('type') == 'JHS') ? 'selected' : ''}}>Junior High School</option>
                                @endif
                                @if(in_array("SHS", explode(", ", $s->school_type)))
                                <option value="SHS" {{(old('type') == 'SHS') ? 'selected' : ''}}>Senior High School</option>
                                @endif
                                @if(in_array("COLLEGE", explode(", ", $s->school_type)))
                                <option value="COLLEGE" {{(old('type') == 'COLLEGE') ? 'selected' : ''}}>College</option>
                                @endif
                                @if(in_array("VOCATIONAL", explode(", ", $s->school_type)))
                                <option value="VOCATIONAL" {{(old('type') == 'VOCATIONAL') ? 'selected' : ''}}>Vocational</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="level_name"><b class="text-danger">*</b>Group Level Name</label>
                            <input type="text" class="form-control" name="level_name" id="level_name" value="{{old('level_name')}}" style="text-transform: uppercase;" placeholder="ex. Grade X | Grade Y | Course" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="text-center mt-3">
        <p>CESU General Trias: School Based Disease Surveillance System - Voluntarily Developed and Mantained by CJH</p>
    </div>
@endsection