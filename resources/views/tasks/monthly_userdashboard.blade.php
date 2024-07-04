@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Monthly Accomplishment Report for {{Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('F Y')}}</b></div>
            <div class="card-body">
                
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="changeMonth" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Load Monthly Accomplishment Report</h5>
                    </div>
                    <div class="modal-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Year</label>
                            <input type="number" class="form-control" name="year" id="year" min="2022" max="{{date('Y')}}" value="{{date('Y')}}" required>
                          </div>
                        <div class="form-group">
                            <label for="month"><b class="text-danger">*</b>Select Month</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Submit and View</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        @if(!request()->input('year') && !request()->input('month'))
        $('#changeMonth').modal({backdrop: 'static', keyboard: false});
        $('#changeMonth').modal('show');
        @endif
    </script>
@endsection