<p>Facility: <b>{{$f->facility_name}}</b></p>
<p>Reporting for <b>Morbidity Week: {{$d->week}}</b> - <b> Year: {{$d->year}}</b></p>
<br>
@if($d->status == 'ZERO CASE')
<p>Status: <b>ZERO REPORT</b></p>
@else
<p>Status: <b>SUBMITTED</b></p>
<p>Please see the attached file for the weekly report.</p>
@endif
<br>
<p>Thank you.</p>
<br>
<p>--------------------</p>
<small>Sent via General Trias CESU Multi-Program Surveillance System. Developed and maintained by Christian James Historillo.</small>