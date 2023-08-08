<div>
    <p>Good Day!</p>
    <p>Please see the attached file for the PIDSR Weekly Notifiable Diseases Report.</p>
    <br><br>
    @php
        $afp_list = [];
        $aef_list = [];
        $ant_list = [];
        $hai_list = []; //0
        $mea_list = [];
        $mgc_list = [];
        $nt_list = [];
        $psp_list = [];
        $rab_list = [];
        $sar_list = []; //0
        $hfm_list = [];
        $abd_list = [];
        $aes_list = [];
        $ahf_list = [];
        $hep_list = [];
        $ame_list = [];
        $mgt_list = [];
        $chi_list = [];
        $cho_list = [];
        $den_list = [];
        $dip_list = [];
        $ili_list = [];
        $lep_list = [];
        $mal_list = [];
        $nnt_list = [];
        $per_list = [];
        $rtv_list = [];
        $typ_list = [];
    @endphp
    @foreach($list as $l)
        @php
        if($l['type'] == 'Acute Flaccid Paralysis') {
            array_push($afp_list, $l);
        }
        else if($l['type'] == 'AEFI') {
            array_push($aef_list, $l);
        }
        else if($l['type'] == 'Anthrax') {
            array_push($ant_list, $l);
        }
        else if($l['type'] == 'Measles') {
            array_push($mea_list, $l);
        }
        else if($l['type'] == 'Meningococcal Disease') {
            array_push($mgc_list, $l);
        }
        else if($l['type'] == 'Neonatal Tetanus') {
            array_push($nt_list, $l);
        }
        else if($l['type'] == 'Paralytic Shellfish Poisoning') {
            array_push($psp_list, $l);
        }
        else if($l['type'] == 'Rabies') {
            array_push($rab_list, $l);
        }
        else if($l['type'] == 'Acute Bloody Diarrhea') {
            array_push($abd_list, $l);
        }
        else if($l['type'] == 'Acute Encephalitis Syndrome') {
            array_push($aes_list, $l);
        }
        else if($l['type'] == 'Acute Hemorrhagic Fever Syndrome') {
            array_push($ahf_list, $l);
        }
        else if($l['type'] == 'Acute Viral Hepatitis') {
            array_push($hep_list, $l);
        }
        else if($l['type'] == 'AMES') {
            array_push($ame_list, $l);
        }
        else if($l['type'] == 'Bacterial Meningitis') {
            array_push($mgt_list, $l);
        }
        else if($l['type'] == 'Chikungunya') {
            array_push($chi_list, $l);
        }
        else if($l['type'] == 'Cholera') {
            array_push($cho_list, $l);
        }
        else if($l['type'] == 'Dengue') {
            array_push($den_list, $l);
        }
        else if($l['type'] == 'Diphtheria') {
            array_push($dip_list, $l);
        }
        else if($l['type'] == 'Influenza-like Illness') {
            array_push($ili_list, $l);
        }
        else if($l['type'] == 'Leptospirosis') {
            array_push($lep_list, $l);
        }
        else if($l['type'] == 'Malaria') {
            array_push($mal_list, $l);
        }
        else if($l['type'] == 'Non-Neonatal Tetanus') {
            array_push($nnt_list, $l);
        }
        else if($l['type'] == 'Pertussis') {
            array_push($per_list, $l);
        }
        else if($l['type'] == 'RotaVirus') {
            array_push($rtv_list, $l);
        }
        else if($l['type'] == 'Typhoid and Parathyphoid Fever') {
            array_push($typ_list, $l);
        }
        else if($l['type'] == 'Hfmd') {
            array_push($hfm_list, $l);
        }
        @endphp
    @endforeach

    @php
    usort($afp_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($aef_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });
    
    usort($ant_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($mea_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($mgc_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($nt_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($psp_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($rab_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($abd_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($aes_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($ahf_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($hep_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($ame_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($mgt_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($chi_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($cho_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($den_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($dip_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($ili_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($lep_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($mal_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($nnt_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($per_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($rtv_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($typ_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });

    usort($hfm_list, function($a, $b) {
        return strcmp($a['brgy'], $b['brgy']);
    });
    
    @endphp
    
    @if(!empty($afp_list) || !empty($aef_list) || !empty($ant_list) || !empty($hfm_list) || !empty($mea_list) || !empty($mgc_list) || !empty($nt_list) || !empty($psp_list) || !empty($rab_list))
    <p><b>Category I (Immediately Notifiable)</b></p>
    <h2 style="color: red;">MW{{date('W, Y', strtotime('-1 Week'))}}</h2>
    @if(!empty($afp_list))
    <ul>
        <b>Acute Flaccid Paralysis:</b>
        @foreach($afp_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($aef_list))
    <ul>
        <b>Adverse Event Following Immunization (AEFI):</b>
        @foreach($aef_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | {{$p['aefi_type']}} Case | Date Admitted: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ant_list))
    <ul>
        <b>Anthrax:</b>
        @foreach($ant_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($hfm_list))
    <ul>
        <b>HFMD:</b>
        @foreach($hfm_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mea_list))
    <ul>
        <b>Measles:</b>
        @foreach($mea_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgc_list))
    <ul>
        <b>Meningococcal Disease:</b>
        @foreach($mgc_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($nt_list))
    <ul>
        <b>Neonatal Tetanus:</b>
        @foreach($nt_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($psp_list))
    <ul>
        <b>Paralytic Shellfish Poisoning:</b>
        @foreach($psp_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($rab_list))
    <ul>
        <b>Rabies:</b>
        @foreach($rab_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif

    @endif

    @if(!empty($abd_list) || !empty($aes_list) || !empty($ahf_list) || !empty($hep_list) || !empty($ame_list) || !empty($mgt_list) || !empty($chi_list) || !empty($cho_list) || !empty($den_list) || !empty($dip_list) || !empty($ili_list) || !empty($lep_list) || !empty($mal_list) || !empty($nnt_list) || !empty($per_list) || !empty($rtv_list) || !empty($typ_list))
    <p><b>Category II (Weekly Notifiable)</b></p>
    <h2 style="color: red;">MW{{date('W, Y', strtotime('-1 Week'))}}</h2>
    @if(!empty($abd_list))
    <ul>
        <b>Acute Bloody Diarrhea:</b>
        @foreach($abd_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($aes_list))
    <ul>
        <b>Acute Encephalitis Syndrome:</b>
        @foreach($aes_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ahf_list))
    <ul>
        <b>Acute Hemorrhagic Fever Syndrome:</b>
        @foreach($ahf_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($hep_list))
    <ul>
        <b>Acute Viral Hepatitis:</b>
        @foreach($hep_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ame_list))
    <ul>
        <b>AMES:</b>
        @foreach($ame_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgt_list))
    <ul>
        <b>Bacterial Meningitis:</b>
        @foreach($mgt_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($chi_list))
    <ul>
        <b>Chikungunya:</b>
        @foreach($chi_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($cho_list))
    <ul>
        <b>Cholera:</b>
        @foreach($cho_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($den_list))
    <ul>
        <b>Dengue:</b>
        @foreach($den_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($dip_list))
    <ul>
        <b>Diphtheria:</b>
        @foreach($dip_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ili_list))
    <ul>
        <b>Influenza-like Illness:</b>
        @foreach($ili_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($lep_list))
    <ul>
        <b>Leptospirosis:</b>
        @foreach($lep_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mal_list))
    <ul>
        <b>Malaria:</b>
        @foreach($mal_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($nnt_list))
    <ul>
        <b>Non-Neonatal Tetanus:</b>
        @foreach($nnt_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($per_list))
    <ul>
        <b>Pertussis:</b>
        @foreach($per_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($rtv_list))
    <ul>
        <b>RotaVirus:</b>
        @foreach($rtv_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($typ_list))
    <ul>
        <b>Typhoid and Parathyphoid Fever:</b>
        @foreach($typ_list as $ind => $p)
        <li>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b> | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}} | DRU: {{mb_strtoupper($p['dru'])}}</li>
        @endforeach
    </ul>
    @endif
    
    @endif
    <p>= = = = =</p>
    <h3>To view the full details of the patients in this lists, you may visit <b><a href="{{route('pidsr.weeklyviewer', ['year' => date('Y'), 'mw' => date('W', strtotime('-1 Week'))])}}">{{route('pidsr.weeklyviewer', ['year' => date('Y'), 'mw' => date('W', strtotime('-1 Week'))])}}</a></b></h3>
    <h4>Use the Password: <b>cesugentri@2017</b></h4>
    <p>= = = = =</p>
    <p>Note: Computer Generated file, Do Not Reply. Made possible by Christian James Historillo.</p>
</div>