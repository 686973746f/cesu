@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Social Hygiene TCL</b></div>
            <div class="card-body">
                <form action="">

                </form>
                <hr>
                <form action="">
                    <input type="hidden" name="month" value="{{ request()->input('month') ?? date('m') }}">
                    <input type="hidden" name="year" value="{{ request()->input('year') ?? date('Y') }}">

                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2">Barangay</th>
                                <th colspan="3">Pregnant women screened for syphilis</th>
                                <th colspan="3">Pregnant women screened reactive for syphilis</th>
                                <th colspan="3">Pregnant women treated for syphilis</th>
                                <th colspan="3">Pregnant women screened for HIV</th>
                                <th colspan="3">Pregnant women screened reactive for HIV</th>
                                <th colspan="3">Pregnant women screened for Hepatitis B</th>
                                <th colspan="3">Pregnant women screened reactive for Hepatitis B</th>
                            </tr>
                            <tr>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                                <th>10-14 years old</th>
                                <th>15-19 years old</th>
                                <th>20-49 years old</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $l)
                            <input type="hidden" name="brgy_id[]" value="{{ $l->id }}">
                            <tr>
                                <td>{{ $l->name }}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_a[]" value="{{old('r_preg_syphilis_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_b[]" value="{{old('r_preg_syphilis_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_c[]" value="{{old('r_preg_syphilis_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_a[]" value="{{old('nr_preg_syphilis_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_b[]" value="{{old('nr_preg_syphilis_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_c[]" value="{{old('nr_preg_syphilis_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_a[]" value="{{old('treated_preg_syphilis_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_b[]" value="{{old('treated_preg_syphilis_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_c[]" value="{{old('treated_preg_syphilis_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_a[]" value="{{old('r_preg_hiv_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_b[]" value="{{old('r_preg_hiv_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_c[]" value="{{old('r_preg_hiv_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_a[]" value="{{old('nr_preg_hiv_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_b[]" value="{{old('nr_preg_hiv_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_c[]" value="{{old('nr_preg_hiv_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_a[]" value="{{old('r_preg_hepab_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_b[]" value="{{old('r_preg_hepab_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_c[]" value="{{old('r_preg_hepab_c')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_a[]" value="{{old('nr_preg_hepab_a')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_b[]" value="{{old('nr_preg_hepab_b')[$ind] ?? 0}}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_c[]" value="{{old('nr_preg_hepab_c')[$ind] ?? 0}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection