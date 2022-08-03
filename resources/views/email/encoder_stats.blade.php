@component('mail::message')
    <div>
        @component('mail::table')
        @php
        $gt_suspected = 0;
        $gt_confirmed = 0;
        $gt_negative = 0;
        $gt_recovered = 0;
        @endphp
        | # | Name | No. of Suspected/Probable Patient Encoded | No. of Confirmed Patient Encoded | No. of Recovered Patient Encoded | No. of Negative Patient Encoded | Total |
        | - |:----:| :----------------------------------------:|:--------------------------------:|:--------------------------------:|:-------------------------------:|------:|
        @foreach($arr as $i)
        | {{$loop->iteration}} | {{$i['name']}} | {{$i['suspected_count']}} | {{$i['confirmed_count']}} | {{$i['recovered_count']}} | {{$i['negative_count']}} | {{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count']}} |
        @php
        $gt_suspected += $i['suspected_count'];
        $gt_confirmed += $i['confirmed_count'];
        $gt_negative += $i['negative_count'];
        $gt_recovered += $i['recovered_count'];
        @endphp
        @endforeach
        | _ | TOTAL | {{$gt_suspected}} | {{$gt_confirmed}} | {{$gt_recovered}} | {{$gt_negative}} | {{$gt_suspected + $gt_confirmed + $gt_negative + $gt_recovered}} |
        @endcomponent
    </div>
@endcomponent