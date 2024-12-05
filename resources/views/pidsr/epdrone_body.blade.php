@if(session('msg'))
<div class="alert alert-{{session('msgtype')}}" role="alert">
    {{session('msg')}}
</div>
@endif
<div class="row">
    <div class="col-md-3 mb-3">
        <a href="{{$abd_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Bloody Diarhhea</h4>
                    <h4 class="text-warning">{{$abd_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$abd_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$aes_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Encephalitis Syndrome</h4>
                    <h4 class="text-warning">{{$aes_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$aes_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$afp_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Flaccid Paralysis</h4>
                    <h4 class="text-warning">{{$afp_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$afp_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$ahf_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Hemorrhagic Fever</h4>
                    <h4 class="text-warning">{{$ahf_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$ahf_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$ames_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Meningitis Encephalitis (AMES)</h4>
                    <h4 class="text-warning">{{$ames_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$ames_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$aefi_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">AEFI</h4>
                    <h4 class="text-warning">{{$aefi_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$aefi_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$anthrax_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Anthrax</h4>
                    <h4 class="text-warning">{{$anthrax_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$anthrax_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$chikv_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Chikungunya Viral Disease</h4>
                    <h4 class="text-warning">{{$chikv_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$chikv_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$cholera_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Cholera</h4>
                    <h4 class="text-warning">{{$cholera_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$cholera_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$covid_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">COVID-19</h4>
                    <h4 class="text-warning">{{$covid_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$covid_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$dengue_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Dengue</h4>
                    <h4 class="text-warning">{{$dengue_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$dengue_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$diph_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Diphtheria</h4>
                    <h4 class="text-warning">{{$diph_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$diph_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$hfmd_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Hand, Foot & Mouth Disease</h4>
                    <h4 class="text-warning">{{$hfmd_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$hfmd_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$hepa_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Hepatitis</h4>
                    <h4 class="text-warning">{{$hepa_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$hepa_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$ili_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Influenza-like Illness</h4>
                    <h4 class="text-warning">{{$ili_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$ili_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$lepto_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Leptospirosis</h4>
                    <h4 class="text-warning">{{$lepto_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$lepto_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$malaria_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Malaria</h4>
                    <h4 class="text-warning">{{$malaria_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$malaria_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$measles_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Measles</h4>
                    <h4 class="text-warning">{{$measles_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$measles_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$meningitis_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Meningitis</h4>
                    <h4 class="text-warning">{{$meningitis_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$meningitis_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$meningo_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Meningococcal Disease</h4>
                    <h4 class="text-warning">{{$meningo_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$meningo_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$mpox_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">MPox</h4>
                    <h4 class="text-warning">{{$mpox_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$mpox_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$nt_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Neonatal Tetanus</h4>
                    <h4 class="text-warning">{{$nt_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$nt_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$nnt_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Non-Neonatal Tetanus</h4>
                    <h4 class="text-warning">{{$nnt_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$nnt_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @if($year < 2024)
    <div class="col-md-3 mb-3">
        <a href="{{$psp_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Paralytic Shellfish Poisoning</h4>
                    <h4 class="text-warning">{{$psp_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$psp_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <a href="{{$pert_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Pertussis</h4>
                    <h4 class="text-warning">{{$pert_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$pert_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$rabies_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Rabies</h4>
                    <h4 class="text-warning">{{$rabies_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$rabies_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$rotavirus_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Rotavirus</h4>
                    <h4 class="text-warning">{{$rotavirus_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$rotavirus_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$sari_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Severe Acute Respiratory Infection (SARI)</h4>
                    <h4 class="text-warning">{{$sari_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$sari_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{$typhoid_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Typhoid and Paratyphoid Fever</h4>
                    <h4 class="text-warning">{{$typhoid_count}}</h4>
                    <h4 class="text-danger">Deaths: {{$typhoid_count_death}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>