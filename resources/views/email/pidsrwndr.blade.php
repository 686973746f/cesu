<div>
    <p>Good Day!</p>
    <p>Please see the attached file for the PIDSR Weekly Notifiable Diseases Report.</p>
    <p>List:</p>
    @php
        $afp_list = [];
        $aef_list = []; //0
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
        } else if($l['type'] == 'Anthrax') {
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
    
    @if(!empty($afp_list) || !empty($ant_list) || !empty($hfm_list) || !empty($mea_list) || !empty($mgc_list) || !empty($nt_list) || !empty($psp_list) || !empty($rab_list))
    <p><b>Category I (Immediately Notifiable)</b></p>
    @if(!empty($afp_list))
    <ul>
        Acute Flaccid Paralysis:
        @foreach($afp_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ant_list))
    <ul>
        Anthrax:
        @foreach($ant_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($hfm_list))
    <ul>
        HFMD:
        @foreach($hfm_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mea_list))
    <ul>
        Measles:
        @foreach($mea_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgc_list))
    <ul>
        Meningococcal Disease:
        @foreach($mgc_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($nt_list))
    <ul>
        Neonatal Tetanus:
        @foreach($nt_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($psp_list))
    <ul>
        Paralytic Shellfish Poisoning:
        @foreach($psp_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($rab_list))
    <ul>
        Rabies:
        @foreach($rab_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif

    @endif

    @if(!empty($abd_list) || !empty($aes_list) || !empty($ahf_list) || !empty($hep_list) || !empty($ame_list) || !empty($mgt_list) || !empty($chi_list) || !empty($cho_list) || !empty($den_list) || !empty($dip_list) || !empty($ili_list) || !empty($lep_list) || !empty($mal_list) || !empty($nnt_list) || !empty($per_list) || !empty($rtv_list) || !empty($typ_list))
    <p><b>Category II (Weekly Notifiable)</b></p>
    @if(!empty($abd_list))
    <ul>
        Acute Bloody Diarrhea:
        @foreach($abd_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($aes_list))
    <ul>
        Acute Encephalitis Syndrome:
        @foreach($aes_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ahf_list))
    <ul>
        Acute Hemorrhagic Fever Syndrome:
        @foreach($ahf_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($hep_list))
    <ul>
        Acute Viral Hepatitis:
        @foreach($hep_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ame_list))
    <ul>
        AMES:
        @foreach($ame_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgt_list))
    <ul>
        Bacterial Meningitis:
        @foreach($mgt_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($chi_list))
    <ul>
        Chikungunya:
        @foreach($chi_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($cho_list))
    <ul>
        Cholera:
        @foreach($cho_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($den_list))
    <ul>
        Dengue:
        @foreach($den_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($dip_list))
    <ul>
        Diphtheria:
        @foreach($dip_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($ili_list))
    <ul>
        Influenza-like Illness:
        @foreach($ili_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($lep_list))
    <ul>
        Leptospirosis:
        @foreach($lep_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($mal_list))
    <ul>
        Malaria:
        @foreach($mal_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($nnt_list))
    <ul>
        Non-Neonatal Tetanus:
        @foreach($nnt_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($per_list))
    <ul>
        Pertussis:
        @foreach($per_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($rtv_list))
    <ul>
        RotaVirus:
        @foreach($rtv_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    @if(!empty($typ_list))
    <ul>
        Typhoid and Parathyphoid Fever:
        @foreach($typ_list as $ind => $p)
        <li>{{($ind + 1)}}.) {{$p['name']}} | {{$p['age']}}/{{$p['sex']}} | BRGY. {{mb_strtoupper($p['address'])}} | Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</li>
        @endforeach
    </ul>
    @endif
    
    @endif
    <p>-</p>
    <p>Note: Computer Generated file, Do Not Reply. Made possible by Christian James Historillo.</p>
</div>