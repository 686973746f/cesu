@component('mail::message')
    <div>
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
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td>{{$i['name']}}</td>
                    <td class="text-center">{{$i['suspected_count']}}</td>
                    <td class="text-center" style="color: red;">{{$i['confirmed_count']}}</td>
                    <td class="text-center" style="color: green;">{{$i['recovered_count']}}</td>
                    <td class="text-center">{{$i['negative_count']}}</td>
                    <td class="text-center font-weight-bold">{{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count']}}</td>
                </tr>
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
    </div>
@endcomponent