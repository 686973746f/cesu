<div>
    <p>Good Day.</p>
    <p>Please see the list below for the cases imported from EDCS-IS:</p>
    <p><b>Note:</b> The list should be informed to their respective barangays for verification and monitoring.</p>
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
        $barangay = $l['brgy'];
        if (!isset($afp_list[$barangay])) {
            $afp_list[$barangay] = [];
        }

        $afp_list[$barangay][] = $l;

        //array_push($afp_list, $l);
    }
    else if($l['type'] == 'AEFI') {
        $barangay = $l['brgy'];
        if (!isset($aef_list[$barangay])) {
            $aef_list[$barangay] = [];
        }

        $aef_list[$barangay][] = $l;
        
        //array_push($aef_list, $l);
    }
    else if($l['type'] == 'Anthrax') {
        $barangay = $l['brgy'];
        if (!isset($ant_list[$barangay])) {
            $ant_list[$barangay] = [];
        }

        $ant_list[$barangay][] = $l;

        //array_push($ant_list, $l);
    }
    else if($l['type'] == 'Measles') {
        $barangay = $l['brgy'];
        if (!isset($mea_list[$barangay])) {
            $mea_list[$barangay] = [];
        }

        $mea_list[$barangay][] = $l;

        //array_push($mea_list, $l);
    }
    else if($l['type'] == 'Meningococcal Disease') {
        $barangay = $l['brgy'];
        if (!isset($mgc_list[$barangay])) {
            $mgc_list[$barangay] = [];
        }

        $mgc_list[$barangay][] = $l;
        
        //array_push($mgc_list, $l);
    }
    else if($l['type'] == 'Neonatal Tetanus') {
        $barangay = $l['brgy'];
        if (!isset($nt_list[$barangay])) {
            $nt_list[$barangay] = [];
        }

        $nt_list[$barangay][] = $l;

        //array_push($nt_list, $l);
    }
    else if($l['type'] == 'Paralytic Shellfish Poisoning') {
        $barangay = $l['brgy'];
        if (!isset($psp_list[$barangay])) {
            $psp_list[$barangay] = [];
        }

        $psp_list[$barangay][] = $l;

        //array_push($psp_list, $l);
    }
    else if($l['type'] == 'Rabies') {
        $barangay = $l['brgy'];
        if (!isset($rab_list[$barangay])) {
            $rab_list[$barangay] = [];
        }

        $rab_list[$barangay][] = $l;

        //array_push($rab_list, $l);
    }
    else if($l['type'] == 'Acute Bloody Diarrhea') {
        $barangay = $l['brgy'];
        if (!isset($abd_list[$barangay])) {
            $abd_list[$barangay] = [];
        }

        $abd_list[$barangay][] = $l;

        //array_push($abd_list, $l);
    }
    else if($l['type'] == 'Acute Encephalitis Syndrome') {
        $barangay = $l['brgy'];
        if (!isset($aes_list[$barangay])) {
            $aes_list[$barangay] = [];
        }

        $aes_list[$barangay][] = $l;

        //array_push($aes_list, $l);
    }
    else if($l['type'] == 'Acute Hemorrhagic Fever Syndrome') {
        $barangay = $l['brgy'];
        if (!isset($ahf_list[$barangay])) {
            $ahf_list[$barangay] = [];
        }

        $ahf_list[$barangay][] = $l;

        //array_push($ahf_list, $l);
    }
    else if($l['type'] == 'Acute Viral Hepatitis') {
        $barangay = $l['brgy'];
        if (!isset($hep_list[$barangay])) {
            $hep_list[$barangay] = [];
        }

        $hep_list[$barangay][] = $l;

        //array_push($hep_list, $l);
    }
    else if($l['type'] == 'AMES') {
        $barangay = $l['brgy'];
        if (!isset($ame_list[$barangay])) {
            $ame_list[$barangay] = [];
        }

        $ame_list[$barangay][] = $l;

        //array_push($ame_list, $l);
    }
    else if($l['type'] == 'Bacterial Meningitis') {
        $barangay = $l['brgy'];
        if (!isset($mgt_list[$barangay])) {
            $mgt_list[$barangay] = [];
        }

        $mgt_list[$barangay][] = $l;

        //array_push($mgt_list, $l);
    }
    else if($l['type'] == 'Chikungunya') {
        $barangay = $l['brgy'];
        if (!isset($chi_list[$barangay])) {
            $chi_list[$barangay] = [];
        }

        $chi_list[$barangay][] = $l;

        //array_push($chi_list, $l);
    }
    else if($l['type'] == 'Cholera') {
        $barangay = $l['brgy'];
        if (!isset($cho_list[$barangay])) {
            $cho_list[$barangay] = [];
        }

        $cho_list[$barangay][] = $l;

        //array_push($cho_list, $l);
    }
    else if($l['type'] == 'Dengue') {
        $barangay = $l['brgy'];
        if (!isset($den_list[$barangay])) {
            $den_list[$barangay] = [];
        }

        $den_list[$barangay][] = $l;

        //array_push($den_list, $l);
    }
    else if($l['type'] == 'Diphtheria') {
        $barangay = $l['brgy'];
        if (!isset($dip_list[$barangay])) {
            $dip_list[$barangay] = [];
        }

        $dip_list[$barangay][] = $l;

        //array_push($dip_list, $l);
    }
    else if($l['type'] == 'Influenza-like Illness') {
        $barangay = $l['brgy'];
        if (!isset($ili_list[$barangay])) {
            $ili_list[$barangay] = [];
        }

        $ili_list[$barangay][] = $l;

        //array_push($ili_list, $l);
    }
    else if($l['type'] == 'Leptospirosis') {
        $barangay = $l['brgy'];
        if (!isset($lep_list[$barangay])) {
            $lep_list[$barangay] = [];
        }

        $lep_list[$barangay][] = $l;

        //array_push($lep_list, $l);
    }
    else if($l['type'] == 'Malaria') {
        $barangay = $l['brgy'];
        if (!isset($mal_list[$barangay])) {
            $mal_list[$barangay] = [];
        }

        $mal_list[$barangay][] = $l;

        //array_push($mal_list, $l);
    }
    else if($l['type'] == 'Non-Neonatal Tetanus') {
        $barangay = $l['brgy'];
        if (!isset($nnt_list[$barangay])) {
            $nnt_list[$barangay] = [];
        }

        $nnt_list[$barangay][] = $l;

        //array_push($nnt_list, $l);
    }
    else if($l['type'] == 'Pertussis') {
        $barangay = $l['brgy'];
        if (!isset($per_list[$barangay])) {
            $per_list[$barangay] = [];
        }

        $per_list[$barangay][] = $l;

        //array_push($per_list, $l);
    }
    else if($l['type'] == 'RotaVirus') {
        $barangay = $l['brgy'];
        if (!isset($rtv_list[$barangay])) {
            $rtv_list[$barangay] = [];
        }

        $rtv_list[$barangay][] = $l;

        //array_push($rtv_list, $l);
    }
    else if($l['type'] == 'Typhoid and Parathyphoid Fever') {
        $barangay = $l['brgy'];
        if (!isset($typ_list[$barangay])) {
            $typ_list[$barangay] = [];
        }

        $typ_list[$barangay][] = $l;

        //array_push($typ_list, $l);
    }
    else if($l['type'] == 'HFMD') {
        $barangay = $l['brgy'];
        if (!isset($hfm_list[$barangay])) {
            $hfm_list[$barangay] = [];
        }

        $hfm_list[$barangay][] = $l;

        //array_push($hfm_list, $l);
    }
    @endphp
    @endforeach

    @if(!empty($afp_list) || !empty($aef_list) || !empty($ant_list) || !empty($hfm_list) || !empty($mea_list) || !empty($mgc_list) || !empty($nt_list) || !empty($psp_list) || !empty($rab_list))
    <p><b>Category I (Immediately Notifiable)</b></p>
    <h2 style="color: red;">MW{{date('W, Y', strtotime('-1 Week'))}}</h2>
    @if(!empty($afp_list))
    <ul>
        <b>Acute Flaccid Paralysis:</b>
        @foreach($afp_list as $brgy => $rows)
        <li>Acute Flaccid Paralysis <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($aef_list))
    <ul>
        <b>Adverse Event Following Immunization (AEFI):</b>
        @foreach($aef_list as $brgy => $rows)
        <li>Adverse Event Following Immunization (AEFI) <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{($ind + 1)}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>{{$p['aefi_type']}} Case</div>
                <div>Date Admitted: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($hfm_list))
    <ul>
        <b>HFMD:</b>
        @foreach($hfm_list as $brgy => $rows)
        <li>HFMD <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($mea_list))
    <ul>
        <b>Measles:</b>
        @foreach($mea_list as $brgy => $rows)
        <li>Measles <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgc_list))
    <ul>
        <b>Meningococcal Disease:</b>
        @foreach($mgc_list as $brgy => $rows)
        <li>Meningococcal Disease <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($nt_list))
    <ul>
        <b>Neonatal Tetanus:</b>
        @foreach($nt_list as $brgy => $rows)
        <li>Neonatal Tetanus <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($rab_list))
    <ul>
        <b>Rabies:</b>
        @foreach($rab_list as $brgy => $rows)
        <li>Rabies <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
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
        @foreach($abd_list as $brgy => $rows)
        <li>Acute Bloody Diarrhea <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($aes_list))
    <ul>
        <b>Acute Encephalitis Syndrome:</b>
        @foreach($aes_list as $brgy => $rows)
        <li>Acute Encephalitis Syndrome <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($hep_list))
    <ul>
        <b>Acute Viral Hepatitis:</b>
        @foreach($hep_list as $brgy => $rows)
        <li>Acute Viral Hepatitis <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($ame_list))
    <ul>
        <b>Acute Meningitis and Encephalitis Syndrome (AMES):</b>
        @foreach($ame_list as $brgy => $rows)
        <li>Acute Meningitis and Encephalitis Syndrome (AMES) <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($mgt_list))
    <ul>
        <b>Bacterial Meningitis:</b>
        @foreach($mgt_list as $brgy => $rows)
        <li>Bacterial Meningitis <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($chi_list))
    <ul>
        <b>Chikungunya:</b>
        @foreach($chi_list as $brgy => $rows)
        <li>Chikungunya <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($cho_list))
    <ul>
        <b>Cholera:</b>
        @foreach($cho_list as $brgy => $rows)
        <li>Cholera <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($den_list))
    <ul>
        <b>Dengue:</b>
        @foreach($den_list as $brgy => $rows)
        <li>Dengue <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($dip_list))
    <ul>
        <b>Diphtheria:</b>
        @foreach($dip_list as $brgy => $rows)
        <li>Diphtheria <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($ili_list))
    <ul>
        <b>Influenza-like Illness (ILI):</b>
        @foreach($ili_list as $brgy => $rows)
        <li>Influenza-like Illness (ILI) <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($lep_list))
    <ul>
        <b>Leptospirosis:</b>
        @foreach($lep_list as $brgy => $rows)
        <li>Leptospirosis <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($nnt_list))
    <ul>
        <b>Non-Neonatal Tetanus:</b>
        @foreach($nnt_list as $brgy => $rows)
        <li>Non-Neonatal Tetanus <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($per_list))
    <ul>
        <b>Pertussis:</b>
        @foreach($per_list as $brgy => $rows)
        <li>Pertussis <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($rtv_list))
    <ul>
        <b>RotaVirus:</b>
        @foreach($rtv_list as $brgy => $rows)
        <li>RotaVirus <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    @if(!empty($typ_list))
    <ul>
        <b>Typhoid and Parathyphoid Fever:</b>
        @foreach($typ_list as $brgy => $rows)
        <li>Typhoid and Parathyphoid Fever <b>- BRGY. {{$brgy}}</b>:</li>
        <br>
        <ul>
            @foreach($rows as $ind => $p)
            <li>
                <div>{{$ind+1}}.) <b style="color: blue">{{$p['name']}}</b></div>
                <div>{{$p['age']}}/{{$p['sex']}}</div>
                <div>{{mb_strtoupper($p['address'])}}</div>
                <div>Date of Entry: {{date('m/d/Y', strtotime($p['doe']))}}</div>
                <div>DRU: {{mb_strtoupper($p['dru'])}}</div>
                @if(!empty($p['lab_data']))
                <div>Lab Result/s:
                    <ul>
                        @foreach($p['lab_data'] as $pl)
                        <li>* {{$pl['test_type']}} - Collected on: {{date('m/d/Y', strtotime($pl['date_collected']))}} - Result: {{$pl['result']}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <br>
            </li>
            @endforeach
        </ul>
        @endforeach
    </ul>
    @endif
    
    @endif
    <p>= = = = =</p>
    <p>Note: Computer Generated file, Do Not Reply. Made possible by Christian James Historillo.</p>
</div>