@component('mail::message')
    <div>
        @component('mail::table')
        @php
        $gt_suspected = 0;
        $gt_confirmed = 0;
        $gt_negative = 0;
        $gt_recovered = 0;
        @endphp
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>No. of Suspected/Probable Patient Encoded</th>
                    <th style="color: red;">No. of Confirmed Patient Encoded</th>
                    <th style="color: green;">No. of Recovered Patient Encoded</th>
                    <th>No. of Negative Patient Encoded</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arr as $i)
                {{$loop->iteration}}
                {{$i['name']}}
                {{$i['suspected_count']}}
                {{$i['confirmed_count']}}
                {{$i['recovered_count']}}
                {{$i['negative_count']}}
                {{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count']}}
                @php
                $gt_suspected += $i['suspected_count'];
                $gt_confirmed += $i['confirmed_count'];
                $gt_negative += $i['negative_count'];
                $gt_recovered += $i['recovered_count'];
                @endphp
                @endforeach
            </tbody>
            <tfoot class="text-center font-weight-bold">
                <tr>
                    <td colspan="2">TOTAL</td>
                    <td>{{$gt_suspected}}</td>
                    <td style="color: red;">{{$gt_confirmed}}</td>
                    <td style="color: green;">{{$gt_recovered}}</td>
                    <td>{{$gt_negative}}</td>
                    <td>{{$gt_suspected + $gt_confirmed + $gt_negative + $gt_recovered}}</td>
                </tr>
            </tfoot>
        </table>
        @endcomponent
    </div>
@endcomponent