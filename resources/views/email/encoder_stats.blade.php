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
        $gt_opd = 0;
        @endphp
        | # | Name | Suspected/Probable | Confirmed | Recovered | Negative Result | ABTC (New Patients) | VaxCert | OPD | <b>TOTAL</b> |
        | - |:----:| :-----------------:|:---------:|:---------:|:---------------:|:-------------------:|:-------:|:---:|-------------:|
        @foreach($arr as $i)
        | {{$loop->iteration}} | {{$i['name']}} | {{$i['suspected_count']}} | {{$i['confirmed_count']}} | {{$i['recovered_count']}} | {{$i['negative_count']}} | {{$i['abtc_count']}} | {{$i['vaxcert_count']}} | {{$i['opd_count']}} |<b>{{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count'] + $i['abtc_count'] + $i['vaxcert_count'] + $i['opd_count']}}</b> |
        @php
        $gt_suspected += $i['suspected_count'];
        $gt_confirmed += $i['confirmed_count'];
        $gt_negative += $i['negative_count'];
        $gt_recovered += $i['recovered_count'];
        $gt_abtc += $i['abtc_count'];
        $gt_vaxcert += $i['vaxcert_count'];
        $gt_opd += $i['opd_count'];
        @endphp
        @endforeach
        | _ | <b>TOTAL</b> | <b>{{$gt_suspected}}</b> | <b>{{$gt_confirmed}}</b> | <b>{{$gt_recovered}}</b> | <b>{{$gt_negative}}</b> | <b>{{$gt_abtc}}</b> | <b>{{$gt_vaxcert}}</b> | {{$gt_opd}} |<b>{{$gt_suspected + $gt_confirmed + $gt_negative + $gt_recovered + $gt_abtc + $gt_vaxcert + $gt_opd}}</b> |
        @endcomponent
    </div>
@endcomponent