<h4>Facility: <b>{{$f->facility_name}}</b></h4>

@if($d->status == 'ZERO CASE')
<h5><b>ZERO CASE</b> Reported for Morbidity Week: {{$d->week}} - Year: {{$d->year}}</h5>
@else
<h5>Please see attached file for the weekly submission report.</h5>
@endif
<br><br>
<h5>Thank you.</h5>