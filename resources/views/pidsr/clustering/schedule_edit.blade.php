@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('dengue_clustering_update', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Edit Schedule</b> - ID: {{$d->id}}</div>
            <div class="card-body">
                <div class="form-group">
                  <label for="enabled"><b class="text-danger">*</b>Enabled</label>
                  <input type="number" class="form-control" name="enabled" id="enabled" min="0" max="1" value="{{$d->enabled}}" required>
                </div>
                <div class="form-group">
                  <label for="assigned_team"><b class="text-danger">*</b>Responsible Team</label>
                  <select class="form-control" name="assigned_team" id="assigned_team" required>
                    <option value="" disabled {{(is_null(old('assigned_team', $d->assigned_team))) ? 'selected' : ''}}>Choose...</option>
                    <option value="CHO" {{(old('assigned_team', $d->assigned_team)) == 'CHO'}}>CHO</option>
                    <option value="CENRO" {{(old('assigned_team', $d->assigned_team)) == 'CENRO'}}>CENRO</option>
                    <option value="GSO" {{(old('assigned_team', $d->assigned_team)) == 'GSO'}}>GSO</option>
                    <option value="DOH REGIONAL" {{(old('assigned_team', $d->assigned_team)) == 'CHO'}}>DOH REGIONAL</option>
                  </select>
                </div>

                <div class="form-group">
                    <label for="status"><b class="text-danger">*</b>Status</label>
                    <select class="form-control" name="status" id="status" required>
                        @if($d->status == 'PENDING')
                        <option value="PENDING" {{(old('status', $d->assigned_team)) == 'PENDING'}}>PENDING</option>
                        <option value="CYCLE1" {{(old('status', $d->assigned_team)) == 'CYCLE1'}}>1ST CYCLE DONE</option>
                        @else
                        <option value="CYCLE1" {{(old('status', $d->assigned_team)) == 'CYCLE1'}}>1ST CYCLE DONE</option>
                        <option value="CYCLE2" {{(old('status', $d->assigned_team)) == 'CYCLE2'}}>2ND CYCLE DONE</option>
                        <option value="CYCLE3" {{(old('status', $d->assigned_team)) == 'CYCLE3'}}>3RD CYCLE DONE (COMPLETED)</option>
                        <option value="CYCLE4" {{(old('status', $d->assigned_team)) == 'CYCLE4'}}>4TH CYCLE DONE (OPTIONAL)</option>
                        @endif
                    </select>
                </div>

                @if($d->status == 'PENDING')
                <div class="form-group">
                    <label for="cycle1_date">1st Cycle Date</label>
                    <input type="datetime-local" class="form-control" name="cycle1_date" id="cycle1_date" value="{{old('cycle1_date', $d->cycle1_date)}}" aria-describedby="cycle1_date">
                </div>
                @else
                <div class="form-group">
                    <label for="cycle1_date">1st Cycle Date</label>
                    <input type="datetime-local" class="form-control" name="cycle1_date" id="cycle1_date" value="{{old('cycle1_date', $d->cycle1_date)}}" aria-describedby="cycle1_date">
                </div>
                <div class="form-group">
                    <label for="cycle2_date">2nd Cycle Date</label>
                    <input type="datetime-local" class="form-control" name="cycle2_date" id="cycle2_date" value="{{old('cycle2_date', $d->cycle2_date)}}" aria-describedby="cycle2_date">
                </div>
                <div class="form-group">
                    <label for="cycle3_date">3rd Cycle Date</label>
                    <input type="datetime-local" class="form-control" name="cycle3_date" id="cycle3_date" value="{{old('cycle3_date', $d->cycle3_date)}}" aria-describedby="cycle3_date">
                </div>
                <div class="form-group">
                    <label for="cycle4_date">4th Cycle Date (Optional)</label>
                    <input type="datetime-local" class="form-control" name="cycle4_date" id="cycle4_date" value="{{old('cycle4_date', $d->cycle4_date)}}" aria-describedby="cycle4_date">
                </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection