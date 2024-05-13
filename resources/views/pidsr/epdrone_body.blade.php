@if(session('msg'))
<div class="alert alert-{{session('msgtype')}}" role="alert">
    {{session('msg')}}
</div>
@endif
<div class="row">
    <div class="col-3">
        <a href="{{$abd_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Bloody Diarhhea</h4>
                    <h4 class="text-warning">{{$abd_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$afp_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Flaccid Paralysis</h4>
                    <h4 class="text-warning">{{$afp_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$ames_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Meningitis Encephalitis (AMES)</h4>
                    <h4 class="text-warning">{{$ames_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$hepa_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Acute Viral Hepatitis</h4>
                    <h4 class="text-warning">{{$hepa_count}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-3">
        <a href="{{$chikv_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Chikungunya Viral Disease</h4>
                    <h4 class="text-warning">{{$chikv_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$cholera_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Cholera</h4>
                    <h4 class="text-warning">{{$cholera_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$dengue_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Dengue</h4>
                    <h4 class="text-warning">{{$dengue_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$diph_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Diphtheria</h4>
                    <h4 class="text-warning">{{$diph_count}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-3">
        <a href="{{$hfmd_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Hand, Foot & Mouth Disease</h4>
                    <h4 class="text-warning">{{$hfmd_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$ili_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Influenza-like Illness</h4>
                    <h4 class="text-warning">{{$ili_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$lepto_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Leptospirosis</h4>
                    <h4 class="text-warning">{{$lepto_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$measles_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Measles</h4>
                    <h4 class="text-warning">{{$measles_count}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-3">
        <a href="{{$meningo_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Meningococcal Disease</h4>
                    <h4 class="text-warning">{{$meningo_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$nt_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Neonatal Tetanus</h4>
                    <h4 class="text-warning">{{$nt_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$nnt_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Non-Neonatal Tetanus</h4>
                    <h4 class="text-warning">{{$nnt_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$pert_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Pertussis</h4>
                    <h4 class="text-warning">{{$pert_count}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-3">
        <a href="{{$rabies_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Rabies</h4>
                    <h4 class="text-warning">{{$rabies_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$rotavirus_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Rotavirus</h4>
                    <h4 class="text-warning">{{$rotavirus_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$sari_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Severe Acute Respiratory Infection (SARI)</h4>
                    <h4 class="text-warning">{{$sari_count}}</h4>
                </div>
            </div>
        </a>
    </div>
    <div class="col-3">
        <a href="{{$typhoid_route}}">
            <div class="card bg-primary">
                <div class="card-body">
                    <h4 class="text-white">Typhoid and Paratyphoid Fever</h4>
                    <h4 class="text-warning">{{$typhoid_count}}</h4>
                </div>
            </div>
        </a>
    </div>
</div>