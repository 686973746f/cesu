@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<form action="{{route('abtc_walkin_part3')}}" method="POST">
    @csrf
    <div class="modal fade" id="ifbooster" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="is_booster" class="form-label">Ikaw ba ay nabakunahan na ng anti-rabies sa ibang lugar dati pa?</label>
                        <select class="form-select" name="is_booster" id="is_booster" required>
                            <option value="" disabled {{is_null(old('is_booster')) ? 'selected' : ''}}>Pumili...</option>
                            <option value="Y" {{(old('is_booster') == 'Y') ? 'selected' : ''}}>Oo / Yes</option>
                            <option value="N" {{(old('is_booster') == 'N') ? 'selected' : ''}}>Hindi pa / Not yet</option>
                        </select>
                    </div>
                    <div class="d-none" id="booster_note">
                    <p><b>BASAHIN:</b> Kung ikaw ay bakunado na ng anti-rabies noon pa, mayroon ka nang antibodies sa katawan laban sa rabies at ikaw ay isasalang na magpa-booster na lang <i>(dalawang (2) beses ka na lamang babakunahan imbes na tatlo (3))</i>.</p>
                    <p>Ipakita ang lumang form o card ng iyong bakuna kontra rabies sa magbabakuna bilang patunay na ikaw ay bakunado na ng anti-rabies noon.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-block disabled" data-dismiss="modal" id="modal_closebtn">Magpatuloy</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header"><b>Anti-Rabies Vaccination - Walk in Registration ({{session('vaccination_site_name')}})</b></div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header"><b>Personal Information</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="lname" class="form-label"><b class="text-danger">*</b>Apelyido/Surname</label>
                                    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', request()->input('lname'))}}" maxlength="50" placeholder="DELA CRUZ" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fname" class="form-label"><b class="text-danger">*</b>Unang Pangalan/First Name</label>
                                    <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', request()->input('fname'))}}" maxlength="50" placeholder="JUAN" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mname" class="form-label">Gitnang Pangalan/Middle Name</label>
                                    <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', request()->input('mname'))}}" maxlength="50" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', request()->input('suffix'))}}" maxlength="3" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                  <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                                  <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', request()->input('bdate'))}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gender" class="form-label"><span class="text-danger font-weight-bold">*</span>Gender/Kasarian</label>
                                    <select class="form-select" name="gender" id="gender" required>
                                        <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Lalaki/Male</option>
                                        <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Babae/Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label"><span class="text-danger font-weight-bold">*</span>Contact Number (Mobile)</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', '09')}}" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="philhealth" class="form-label">Philhealth <small><i>(Optional)</i></small></label>
                                    <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Address</b></div>
                    <div class="card-body">
                        <div id="address_text" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text')}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row d-none">
                            <div class="col-md-6">
                                <div class="mb-3">
                                  <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                                  <select class="form-select" name="address_region_code" id="address_region_code" required>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                    <select class="form-select" name="address_province_code" id="address_province_code" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                    <select class="form-select" name="address_muncity_code" id="address_muncity_code" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                    <select class="form-select" name="address_brgy_text" id="address_brgy_text" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_houseno" class="form-label"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                    <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex: S1 B2 L3 / #034" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_street" class="form-label"><span class="text-danger font-weight-bold">*</span>Street/Subdivision/Purok/Sitio</label>
                                    <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex: CAMELLA HOMES / PUROK 123 / SITIO ELANG" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Case Information</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Uri ng Hayop na Kumagat</label>
                                    <select class="form-select" name="animal_type" id="animal_type" required>
                                        <option value="" disabled {{is_null(old('animal_type')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="PD" {{(old('animal_type') == 'PD') ? 'selected' : ''}}>Alagang Aso/Pet Dog (PD)</option>
                                        <option value="SD" {{(old('animal_type') == 'SD') ? 'selected' : ''}}>Galang Aso/Stray Dog (SD)</option>
                                        <option value="PC" {{(old('animal_type') == 'PC') ? 'selected' : ''}}>Alagang Pusa/Pet Cat (PC)</option>
                                        <option value="SC" {{(old('animal_type') == 'SC') ? 'selected' : ''}}>Galang Pusa/Stray Cat (SC)</option>
                                        <option value="O" {{(old('animal_type') == 'O') ? 'selected' : ''}}>Iba pa/Others</option>
                                    </select>
                                </div>
                                <div id="ifanimaltype_othersdiv" class="d-none">
                                    <div class="mb-3">
                                        <label for="animal_type_others" class="form-label"><strong class="text-danger">*</strong>Pakitukoy kung anong uri ng hayop ang kumagat/kumalmot</label>
                                        <input type="text" class="form-control" name="animal_type_others" id="animal_type_others" value="{{old('animal_type_others')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bite_date" class="form-label"><strong class="text-danger">*</strong>Kailan nakagat/nakalmot?</label>
                                    <input type="date" class="form-control" name="bite_date" id="bite_date" min="{{date('Y-m-d', strtotime('-1 Month'))}}" max="{{date('Y-m-d')}}" value="{{old('bite_date')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="case_location" class="form-label"><strong id="case_location_ast" class="text-danger">*</strong>Saang Barangay/Siyudad ka nakagat/nakalmot</label>
                                    <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location')}}" placeholder="ex: Loob ng bahay, Kalsada, Gusali, Gubat, atbp." style="text-transform: uppercase;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="body_site" class="form-label"><strong id="body_site_ast" class="text-danger">*</strong>Parte ng katawan na nasugatan/nakagat ng hayop</label>
                                    <input type="text" class="form-control" name="body_site" id="body_site" value="{{old('body_site')}}" placeholder="ex: Kamay, Paa, Hita, atbp." style="text-transform: uppercase;" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="if_animal_vaccinated" class="form-label"><strong class="text-danger">*</strong>Bakunado ba ang hayop na kumagat/kumalmot sa'yo?</label>
                                    <select class="form-select" name="if_animal_vaccinated" id="if_animal_vaccinated" required>
                                        <option value="" disabled {{is_null(old('if_animal_vaccinated')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="Y" {{(old('if_animal_vaccinated') == 'Y') ? 'selected' : ''}}>Oo / Yes</option>
                                        <option value="N" {{(old('if_animal_vaccinated') == 'N') ? 'selected' : ''}}>Hindi / No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="washing_of_bite" class="form-label"><strong class="text-danger">*</strong>Hinugasan na ng sabon ang sugat?</label>
                                    <select class="form-select" name="washing_of_bite" id="washing_of_bite" required>
                                        <option value="" disabled {{is_null(old('washing_of_bite')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="Y" {{(old('washing_of_bite') == 'Y') ? 'selected' : ''}}>Oo / Yes</option>
                                        <option value="N" {{(old('washing_of_bite') == 'N') ? 'selected' : ''}}>Hindi / No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ifbleeding" class="form-label"><strong class="text-danger">*</strong>Dumugo ba ang sugat?</label>
                                    <select class="form-select" name="ifbleeding" id="ifbleeding" required>
                                        <option value="" disabled {{is_null(old('ifbleeding')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="Y" {{(old('ifbleeding') == 'Y') ? 'selected' : ''}}>Oo, dumugo</option>
                                        <option value="N" {{(old('ifbleeding') == 'N') ? 'selected' : ''}}>Hindi, gasgas/galos lang</option>
                                    </select>
                                    <small class="text-muted">Note: Titignan ng magbabakuna kung nasaan ang kagat/sugat.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><b>Data Privacy Statement</b></div>
                    <div class="card-body text-center">
                        <p>Sa pamamagitan ng pagpili ng <i>"Sumasang-ayon ako"</i> at pag-click sa pindutang <i>"Isumite"</i> sa ibaba, kinikilala ko at napatunayan na maingat kong binasa at naintindihan ang mga tuntunin at kundisyon ng patakaran sa Data Privacy/Polisiya ng Pamahalaan ng Lungsod ng General Trias. Sa pamamagitan ng pagbibigay ng personal na impormasyon sa Pamahalaang Lungsod ng General Trias, kinukumpirma ko na ang data ay totoo at tama. Naiintindihan ko na ang Pamahalaang Lungsod ng General Trias ay may karapatang baguhin ang anumang desisyon na ginawa batay sa impormasyong ibinigay ko kung ang impormasyon ay mapatunayan na hindi totoo o hindi tama. Sumasang-ayon din ako na ang anumang isyu na maaaring lumabas na may kaugnayan sa pagproseso ng aking personal na impormasyon ay maaayos sa Pamahalaang Panlungsod ng General Trias bago gamitin ang naaangkop na arbitrasyon o paglilitis sa korte sa loob ng hurisdiksyon ng Pilipinas. Sa wakas, nagbibigay ako ng aking kusang-loob na pahintulot at permiso sa Pamahalaang Lungsod ng General Trias at ang mga kinatawan na pinahintulutan na ligal na maproseso ang aking data / impormasyon.</p>
                        <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="dpsagree" id="dpsagree" required>
                              Sumasang-ayon ako
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Finish</button>
            </div>
        </div>
        <p class="mt-3 text-center">CESU/ABTC System Developed and Maintained by <u>CJH</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>
</form>

<script>
    $('#ifbooster').modal({backdrop: 'static', keyboard: false});
    $('#ifbooster').modal('show');

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    //Region Select Initialize
    $.getJSON("{{asset('json/refregion.json')}}", function(data) {
        var sorted = data.sort(function(a, b) {
            if (a.regDesc > b.regDesc) {
                return 1;
            }
            if (a.regDesc < b.regDesc) {
                return -1;
            }

            return 0;
        });

        $.each(sorted, function(key, val) {
            $('#address_region_code').append($('<option>', {
                value: val.regCode,
                text: val.regDesc,
                selected: (val.regCode == '04') ? true : false, //default is Region IV-A
            }));
        });
    });

    $('#address_region_code').change(function (e) { 
        e.preventDefault();
        //Empty and Disable
        $('#address_province_code').empty();
        $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', true);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_region_text').val($('#address_region_code option:selected').text());

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });

            $.each(sorted, function(key, val) {
                if($('#address_region_code').val() == val.regCode) {
                    $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (val.provCode == '0421') ? true : false, //default for Cavite
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_province_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_province_text').val($('#address_province_code option:selected').text());

        $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                }
                if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_province_code').val() == val.provCode) {
                    $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                    })); 
                }
            });
        });
    }).trigger('change');

    $('#address_muncity_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_brgy_text').empty();
        $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', false);

        //Set Values for Hidden Box
        $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

        $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.brgyDesc > b.brgyDesc) {
                return 1;
                }
                if (a.brgyDesc < b.brgyDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_muncity_code').val() == val.citymunCode) {
                    $('#address_brgy_text').append($('<option>', {
                        value: val.brgyDesc.toUpperCase(),
                        text: val.brgyDesc.toUpperCase(),
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');

    $('#is_booster').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#modal_closebtn').removeClass('disabled');
            $('#booster_note').removeClass('d-none');
        }
        else if($(this).val() == 'N') {
            $('#modal_closebtn').removeClass('disabled');
            $('#booster_note').addClass('d-none');
        }
    });
</script>
@endsection