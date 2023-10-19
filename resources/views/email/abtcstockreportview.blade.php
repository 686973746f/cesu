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
            <li>Stocks Remaining: {{$b['remaining']}}</li>
        </ul>
        <p></p>
        @endforeach
    <p></p>
    @endforeach
</div>