@component('mail::message')
    <div>
        @component('mail::table')
        @php
        $gt_covid = 0;
        $gt_abtc = 0;
        $gt_abtc_ff = 0;
        $gt_vaxcert = 0;
        $gt_opd = 0;
        $gt_livebirth = 0;
        $gt_edcs = 0;
        $gt_death = 0;
        $gt_opdtoics = 0;
        $gt_abtctoics = 0;
        @endphp
        | # | Name | COVID-19 | ABTC (New) | ABTC (FFup) | VaxCert | OPD | LCR Livebirths | Imports from EDCS-IS | Encoded Death Certificates | OPD to iClinicSys | ABTC to iClinicSys | <b>TOTAL</b> |
        | - |:----:|:--------:|:----------:|:-----------:|:-------:|:---:|:--------------:|:--------------------:|:--------------------------:|:-----------------:|:------------------:|-------------:|
        @foreach($arr as $i)
        | {{$loop->iteration}} | <b>{{mb_strtoupper($i['name'])}}</b> | {{$i['covid_count_final']}} | {{$i['abtc_count']}} | {{$i['abtc_ffup_gtotal']}} | {{$i['vaxcert_count']}} | {{$i['opd_count']}} | {{$i['lcr_livebirth']}} | {{$i['edcs_count']}} | {{$i['death_count']}} | {{$i['opdtoics_count']}} | {{$i['abtctoics_count']}} | <b>{{$i['covid_count_final'] + $i['abtc_count'] + $i['vaxcert_count'] + $i['opd_count'] + $i['abtc_ffup_gtotal'] + $i['lcr_livebirth'] + $i['edcs_count'] + $i['death_count'] + $i['opdtoics_count'] + $i['abtctoics_count']}}</b> |
        @php
        $gt_covid += $i['covid_count_final'];
        $gt_abtc += $i['abtc_count'];
        $gt_abtc_ff += $i['abtc_ffup_gtotal'];
        $gt_vaxcert += $i['vaxcert_count'];
        $gt_opd += $i['opd_count'];
        $gt_livebirth += $i['lcr_livebirth'];
        $gt_edcs += $i['edcs_count'];
        $gt_death += $i['death_count'];
        $gt_opdtoics += $i['opdtoics_count'];
        $gt_abtctoics += $i['abtctoics_count'];
        @endphp
        @endforeach
        | _ | <b>TOTAL</b> | <b>{{$gt_covid}}</b> | <b>{{$gt_abtc}}</b> | <b>{{$gt_abtc_ff}}</b> | <b>{{$gt_vaxcert}}</b> | <b>{{$gt_opd}}</b> | <b>{{$gt_livebirth}}</b> | <b>{{$gt_edcs}}</b> | <b>{{$gt_death}}</b> | <b>{{$gt_opdtoics}}</b> | <b>{{$gt_abtctoics}}</b> | <b>{{$gt_covid + $gt_abtc + $gt_vaxcert + $gt_opd + $gt_abtc_ff + $gt_livebirth + $gt_edcs + $gt_death + $gt_opdtoics + $gt_abtctoics}}</b> |
        @endcomponent
    </div>
@endcomponent