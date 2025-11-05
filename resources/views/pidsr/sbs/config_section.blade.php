@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('sbs_viewlevel')}}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>Configure Sections:</b> {{$s->name}}</div>
                        <div>{{$level->level_name}}</div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createSection">Add Section</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgType')}}" role="alert">
                    {{session('msg')}}
                </div>
                <hr>
                @endif

                @if($list->count() != 0)
                <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#createPopulation">Create Population Data</button>
                @endif

                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Section</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td>{{$l->section_name}}</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($l->created_at))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{route('sbs_storesection', $level->id)}}" method="POST">
        @csrf
        <div class="modal fade" id="createSection" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Link to Group Name</label>
                            <input type="text" class="form-control" name="" id="" value="{{$level->level_name}}" style="text-transform: uppercase;" readonly>
                        </div>
                        <div class="form-group">
                            <label for="section_name"><b class="text-danger">*</b>Section Name</label>
                            <input type="text" class="form-control" name="section_name" id="section_name" value="{{old('section_name')}}" style="text-transform: uppercase;" placeholder="EX: Maliksi | 1-4 | Dauntless" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="" method="GET">
        <div class="modal fade" id="createPopulation" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Population Data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Academic Year (AY)</label>
                            <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2025) as $y)
                                <option value="{{$y-1}}-{{$y}}">AY {{$y-1}}-{{$y}}</option>
                                <option value="{{$y}}-{{$y+1}}">AY {{$y}}-{{$y+1}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="text-center mt-3">
        <p>CESU General Trias: School Based Disease Surveillance System - Voluntarily Developed and Mantained by CJH</p>
    </div>
@endsection