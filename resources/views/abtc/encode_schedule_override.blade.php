@extends('layouts.app')

@section('content')
<form action="{{route('override_schedule_process', ['br_id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">Schedule Override</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="alert alert-primary" role="alert">
                    <b class="text-danger">Note:</b> Only the pending schedule can be manually changed.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>Schedule</th>
                                <th>Date</th>
                                <th>Change to</th>
                                <th>Override Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row"><b>Day 0</b></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d0_date))}}</td>
                                <td>
                                    <input type="date" class="form-control" name="d0_date" id="d0_date" value="{{old('d0_date', $d->d0_date)}}" required>
                                </td>
                                <td>
                                    @if($d->d0_done == 0)
                                    <select class="form-select" name="d0_ostatus" id="d0_ostatus" required>
                                        <option value="P" {{(old('d0_ostatus') == 'P') ? 'selected' : ''}}>PENDING</option>
                                        <option value="C" {{(old('d0_ostatus') == 'C') ? 'selected' : ''}}>COMPLETED</option>
                                    </select>
                                    @else
                                    <p class="text-success"><b>DONE</b></p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td scope="row"><b>Day 3</b></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d3_date))}}</td>
                                <td>
                                    <input type="date" class="form-control" name="d3_date" id="d3_date" value="{{old('d3_date', $d->d3_date)}}" required>
                                </td>
                                <td>
                                    @if($d->d3_done == 0)
                                    <select class="form-select" name="d3_ostatus" id="d3_ostatus" required>
                                        <option value="P" {{(old('d3_ostatus') == 'P') ? 'selected' : ''}}>PENDING</option>
                                        <option value="C" {{(old('d3_ostatus') == 'C') ? 'selected' : ''}}>COMPLETED</option>
                                    </select>
                                    @else
                                    <p class="text-success"><b>DONE</b></p>
                                    @endif
                                </td>
                            </tr>
                            @if($d->is_booster == 0)
                            <tr>
                                <td scope="row"><b>Day 7</b></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d7_date))}}</td>
                                <td>
                                    <input type="date" class="form-control" name="d7_date" id="d7_date" value="{{old('d7_date', $d->d7_date)}}" required>
                                </td>
                                <td>
                                    @if($d->d7_done == 0)
                                    <select class="form-select" name="d7_ostatus" id="d7_ostatus" required>
                                        <option value="P" {{(old('d7_ostatus') == 'P') ? 'selected' : ''}}>PENDING</option>
                                        <option value="C" {{(old('d7_ostatus') == 'C') ? 'selected' : ''}}>COMPLETED</option>
                                    </select>
                                    @else
                                    <p class="text-success"><b>DONE</b></p>
                                    @endif
                                </td>
                            </tr>
                            @if($d->pep_route != 'ID')
                            <tr>
                                <td scope="row"><b>Day 14</b></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d14_date))}}</td>
                                <td>
                                    <input type="date" class="form-control" name="d14_date" id="d14_date" value="{{old('d14_date', $d->d14_date)}}" required>
                                </td>
                                <td>
                                    @if($d->d14_done == 0)
                                    <select class="form-select" name="d14_ostatus" id="d14_ostatus" required>
                                        <option value="P" {{(old('d14_ostatus') == 'P') ? 'selected' : ''}}>PENDING</option>
                                        <option value="C" {{(old('d14_ostatus') == 'C') ? 'selected' : ''}}>COMPLETED</option>
                                    </select>
                                    @else
                                    <p class="text-success"><b>DONE</b></p>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td scope="row"><b>Day 28</b></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d28_date))}}</td>
                                <td>
                                    <input type="date" class="form-control" name="d28_date" id="d28_date" value="{{old('d28_date', $d->d28_date)}}" required>
                                </td>
                                <td>
                                    @if($d->d28_done == 0)
                                    <select class="form-select" name="d28_ostatus" id="d28_ostatus" required>
                                        <option value="P" {{(old('d28_ostatus') == 'P') ? 'selected' : ''}}>PENDING</option>
                                        <option value="C" {{(old('d28_ostatus') == 'C') ? 'selected' : ''}}>COMPLETED</option>
                                    </select>
                                    @else
                                    <p class="text-success"><b>DONE</b></p>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if($d->outcome != 'C')
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" id="submitbtn">Save (CTRL + S)</button>
            </div>
            @endif
        </div>
    </div>
</form>

<script>
    $(document).bind('keydown', function(e) {
		if(e.ctrlKey && (e.which == 83)) {
			e.preventDefault();
			$('#submitbtn').trigger('click');
			$('#submitbtn').prop('disabled', true);
			setTimeout(function() {
				$('#submitbtn').prop('disabled', false);
			}, 2000);
			return false;
		}
	});
</script>
@endsection