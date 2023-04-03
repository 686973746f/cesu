@component('mail::message')
    <div>
        @component('mail::table')
        @php
        $gt_suspected = 0;
        $gt_confirmed = 0;
        $gt_negative = 0;
        $gt_recovered = 0;
        $gt_abtc = 0;
        $gt_vaxcert = 0;
        @endphp
        | # | Name | Suspected/Probable | Confirmed | Recovered | Negative Result | ABTC (New Patients) | VaxCert | Total |
        | - |:----:| :-----------------:|:---------:|:---------:|:---------------:|:-------------------:|:-------:|------:|
        @foreach($arr as $i)
        | {{$loop->iteration}} | {{$i['name']}} | {{$i['suspected_count']}} | {{$i['confirmed_count']}} | {{$i['recovered_count']}} | {{$i['negative_count']}} | {{$i['abtc_count']}} | {{$i['vaxcert_count']}} | {{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count'] + $i['abtc_count'] + $i['vaxcert_count']}} |
        @php
        $gt_suspected += $i['suspected_count'];
        $gt_confirmed += $i['confirmed_count'];
        $gt_negative += $i['negative_count'];
        $gt_recovered += $i['recovered_count'];
        $gt_abtc += $i['abtc_count'];
        $gt_vaxcert += $i['vaxcert_count'];
        @endphp
        @endforeach
        | _ | TOTAL | {{$gt_suspected}} | {{$gt_confirmed}} | {{$gt_recovered}} | {{$gt_negative}} | {{$gt_abtc}} | {{$gt_vaxcert}} | {{$gt_suspected + $gt_confirmed + $gt_negative + $gt_recovered + $gt_abtc + $gt_vaxcert}} |
        @endcomponent
    </div>
@endcomponent