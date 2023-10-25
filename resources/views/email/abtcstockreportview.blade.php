<style>
    table, th, td {
        border: 1px solid black;
    }
</style>
<div>
    <div>GENTRIAS RABIES CONTROL PROGRAM</div>
    <div>AUTOMATED DAILY STOCK REPORT</div>
    <p></p>
    @foreach($arr as $ind => $a)
    <div><b>{{$a['branch']}}</b></div>
        @foreach($a['second'] as $b)
            @if(!empty($b['third']))
            <div>{{$b['brand']}}</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number of Patient Vaccinated</th>
                        <th>Vials Used</th>
                        <th>Wastage Input</th>
                        <th>Stocks Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($b['third'] as $c)
                    <tr>
                        <td>{{$c['date']}}</td>
                        <td>{{$c['count']}}</td>
                        <td>{{$c['used_vials']}}</td>
                        <td>{{$c['wastage_count']}}cc</td>
                        <td>{{$c['stock_remaining']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p></p>
            @else
            <div>NO RESULTS FOUND.</div>
            @endif
        @endforeach
    @endforeach
</div>