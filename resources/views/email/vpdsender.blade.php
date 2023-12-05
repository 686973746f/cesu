<div>
    <p>This is CESU Gen. Trias Automated Mail.</p>
    <p></p>
    <p>You received this hourly mail because there was VPD Cases Detected in the PIDSR Database. Please see the list below:</p>

    @if(!empty($diph_array))
    <b>DIPHTHERIA</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(!empty($measles_array))
    <b>MEASLES</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(!empty($afp_array))
    <b>ACUTE FLACCID PARALYSIS</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(!empty($pert_array))
    <b>PERTUSSIS</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(!empty($nnt_array))
    <b>NON-NEONATAL TETANUS</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif

    @if(!empty($nt_array))
    <b>NEONATAL TETANUS</b>
    <div></div>
    <ul>
        @foreach($diph_array as $p)
        <li>
            <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
            <div>{{$p['age']}}/{{$p['sex']}}</div>
            <div>{{mb_strtoupper($p['address'])}}, BRGY. {{mb_strtoupper($p['brgy'])}}</div>
            <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
            <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
            <div></div>
        </li>
        @endforeach
    </ul>
    @endif
</div>