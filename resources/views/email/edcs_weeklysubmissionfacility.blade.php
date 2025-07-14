<p>Facility: <b>{{$f->facility_name}}</b></p>
<p>Reporting for <b>Morbidity Week: {{$d->week}}</b> - <b> Year: {{$d->year}}</b></p>
<br>
@if($d->status == 'ZERO CASE')
<p>Status: <b>ZERO REPORT</b></p>
@else
<p>Status: <b>SUBMITTED</b></p>
<p>Please see the attached file for the weekly report.</p>
@endif

@if(!(Carbon\Carbon::now()->isSameDay($d->created_at)))
<p><b>NOTE: </b>This is to inform you that the report was initially submitted on {{date('M d, Y', strtotime($d->created_at))}}. However, due to technical difficulties encountered during the submission, the automated mail was not sent successfully.</p>
<p>We are resending the report for your reference. Apologies for any inconvenience this may have caused.</p>
@endif
<br>
<p>Thank you.</p>
<br>
<p>--------------------</p>
<small>Sent via General Trias CESU Multi-Program Surveillance System (GTCMPSS). Developed and maintained by CJH.</small>