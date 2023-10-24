<div>
    <div>GENTRIAS RABIES CONTROL PROGRAM - AUTOMATED DAILY STOCK REPORT</div>
    <p></p>
    @foreach($arr as $a)
    <div><b>{{$a['branch']}}</b></div>
        @foreach($a['master_count'] as $ind => $b)
        <div><b>{{$ind}}</b></div>
        <ul>
            <li>Patient Vaccinated: {{$b['count']}}</li>
            <li>Bottles Consumed: {{$b['bottle_used']}}</li>
            @if($wastage_count)
            <li>Wastage Input: {{$wastage_count->wastage_dose_count}} {{Str::plural('Bottle', $wastage_count->wastage_dose_count)}}</li>
            @else
            <li>Wastage Input: 0</li>
            @endif
            <li>Stocks Remaining: {{$b['remaining']}}</li>
        </ul>
        <p></p>
        @endforeach
    <p></p>
    @endforeach
</div>