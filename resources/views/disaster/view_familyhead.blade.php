@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>View Family Head and Members</b></div>
                <div><a href="{{route('disaster_viewfamilies')}}" class="btn btn-secondary">Go Back</a></div>
            </div>
            
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>List of Family Members</div>
                        <div>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId">
                                Add Member
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <table class="table table-bordered table-striped">
                        <thead class="text-center thead-light">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Relationship to Family Head</th>
                                <th>Highest Education Attainment</th>
                                <th>Occupation</th>
                                <th>Created at/by</th>
                                <th>Updated at/by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($member_list as $ind => $m)
                            <tr>
                                <td class="text-center">{{$ind+1}}</td>
                                <td><a href="">{{$m->getName()}}</a></td>
                                <td class="text-center">{{date('m/d/Y', strtotime($m->bdate))}}</td>
                                <td class="text-center">{{$m->getAge()}}</td>
                                <td class="text-center">{{$m->sex}}</td>
                                <td class="text-center">{{$m->relationship_tohead}}</td>
                                <td class="text-center">{{$m->highest_education}}</td>
                                <td class="text-center">{{$m->occupation}}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{route('disaster_storemember', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add Family Member</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                <select class="form-control" name="sex" id="sex" required>
                                  <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                  <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                            <div id="femaleDiv" class="d-none">
                                <div class="form-group">
                                    <label for="is_pregnant"><b class="text-danger">*</b>Is Pregnant?</label>
                                    <select class="form-control" name="is_pregnant" id="is_pregnant">
                                      <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="is_lactating"><b class="text-danger">*</b>Is Lactating?</label>
                                    <select class="form-control" name="is_lactating" id="is_lactating">
                                      <option value="" disabled {{(is_null(old('is_lactating'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('is_lactating') == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('is_lactating') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="relationship_tohead"><span class="text-danger font-weight-bold">*</span>Relationship to the Family Head</label>
                                <select class="form-control" name="relationship_tohead" id="relationship_tohead" required>
                                    <option value="" disabled {{(is_null(old('relationship_tohead'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SPOUSE" {{(old('relationship_tohead') == 'SPOUSE') ? 'selected' : ''}}>Spouse/Asawa</option>
                                    <option value="CHILD" {{(old('relationship_tohead') == 'CHILD') ? 'selected' : ''}}>Child</option>
                                    <option value="OTHERS" {{(old('relationship_tohead') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="highest_education"><span class="text-danger font-weight-bold">*</span>Highest Educational Attainment</label>
                                <select class="form-control" name="highest_education" id="highest_education" required>
                                    <option value="" disabled {{(is_null(old('highest_education'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="NO FORMAL EDUCATION" {{(old('highest_education') == 'NO FORMAL EDUCATION') ? 'selected' : ''}}>No Formal Education/Hindi nakapag-aral</option>
                                    <option value="ELEMENTARY GRADUATE" {{(old('highest_education') == 'ELEMENTARY GRADUATE') ? 'selected' : ''}}>Elementary Graduate</option>
                                    <option value="JUNIOR HIGH SCHOOL GRADUATE" {{(old('highest_education') == 'JUNIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Junior High School Graduate</option>
                                    <option value="SENIOR HIGH SCHOOL GRADUATE" {{(old('highest_education') == 'SENIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Senior High School Graduate</option>
                                    <option value="COLLEGE GRADUATE" {{(old('highest_education') == 'COLLEGE GRADUATE') ? 'selected' : ''}}>College Graduate</option>
                                    <option value="MASTERS DEGREE" {{(old('highest_education') == 'MASTERS DEGREE') ? 'selected' : ''}}>Masters Degree</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_pwd"><span class="text-danger font-weight-bold">*</span>Is PWD</label>
                                <select class="form-control" name="is_pwd" id="is_pwd" required>
                                    <option value="" {{(is_null(old('is_pwd'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pwd') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pwd') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps</label>
                                <select class="form-control" name="is_4ps" id="is_4ps" required>
                                    <option value="" {{(is_null(old('is_4ps'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_4ps') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_4ps') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigent</label>
                                <select class="form-control" name="is_indg" id="is_indg" required>
                                    <option value="" {{(is_null(old('is_indg'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_indg') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_indg') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection