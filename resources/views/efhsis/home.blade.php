@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>eFHSIS Menu</b></div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <button type="button" name="" id="" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cesum2"></button>
            </div>
        </div>
    </div>

    <form action="{{route('fhsis_cesum2')}}" method="GET">
        <div class="modal fade" id="cesum2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">CESU M2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="disease"></label>
                          <select class="form-control" name="disease" id="disease">
                            <option value="" disabled selected>Choose...</option>
                            <option value="Covid">Covid</option>
                            <option value="Dengue">Dengue</option>
                            <option value="AnimalBite">Animal Bite</option>
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2020) as $y)
                                <option value="{{$y}}">{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Select Month</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection