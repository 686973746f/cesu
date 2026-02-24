@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Social Hygiene TCL</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{ session('msg') }}
                </div>
                @endif
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="year">Year</label>
                              <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2024) as $y)
                                <option value="{{$y}}" {{ $selectDate->year == $y ? 'selected' : '' }}>{{$y}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="month">Month</label>
                              <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(1, 12) as $m)
                                <option value="{{$m}}" {{ $selectDate->month == $m ? 'selected' : '' }}>{{date('F', mktime(0, 0, 0, $m, 1))}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" type="submit">Change</button>
                    </div>
                </form>
                <hr>
                <div class="alert alert-info">
                    <h4>Encoding for the month of <b>{{ $selectDate->format('F') }}</b>, Year <b>{{ $selectDate->year }}</b></h4>
                </div>
                <form action="{{ route('etcl_shc_store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="month" value="{{ $selectDate->month }}">
                    <input type="hidden" name="year" value="{{ $selectDate->year }}">
                    <input type="hidden" name="request_uuid" value="{{ Str::uuid() }}">

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
                            @php

                            $d = App\Models\SocialHygieneTcl::where('address_brgy_code', $l->id)
                            ->where('year', $selectDate->year)
                            ->where('month', $selectDate->month)
                            ->first();
                            @endphp
                            <input type="hidden" name="brgy_id[]" value="{{ $l->id }}">
                            <tr>
                                <td>{{ $l->name }}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_a[]" value="{{old('r_preg_syphilis_a')[$ind] ?? $d->r_preg_syphilis_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_b[]" value="{{old('r_preg_syphilis_b')[$ind] ?? $d->r_preg_syphilis_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_syphilis_c[]" value="{{old('r_preg_syphilis_c')[$ind] ?? $d->r_preg_syphilis_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_a[]" value="{{old('nr_preg_syphilis_a')[$ind] ?? $d->nr_preg_syphilis_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_b[]" value="{{old('nr_preg_syphilis_b')[$ind] ?? $d->nr_preg_syphilis_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_syphilis_c[]" value="{{old('nr_preg_syphilis_c')[$ind] ?? $d->nr_preg_syphilis_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_a[]" value="{{old('treated_preg_syphilis_a')[$ind] ?? $d->treated_preg_syphilis_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_b[]" value="{{old('treated_preg_syphilis_b')[$ind] ?? $d->treated_preg_syphilis_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="treated_preg_syphilis_c[]" value="{{old('treated_preg_syphilis_c')[$ind] ?? $d->treated_preg_syphilis_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_a[]" value="{{old('r_preg_hiv_a')[$ind] ?? $d->r_preg_hiv_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_b[]" value="{{old('r_preg_hiv_b')[$ind] ?? $d->r_preg_hiv_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hiv_c[]" value="{{old('r_preg_hiv_c')[$ind] ?? $d->r_preg_hiv_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_a[]" value="{{old('nr_preg_hiv_a')[$ind] ?? $d->nr_preg_hiv_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_b[]" value="{{old('nr_preg_hiv_b')[$ind] ?? $d->nr_preg_hiv_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hiv_c[]" value="{{old('nr_preg_hiv_c')[$ind] ?? $d->nr_preg_hiv_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_a[]" value="{{old('r_preg_hepab_a')[$ind] ?? $d->r_preg_hepab_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_b[]" value="{{old('r_preg_hepab_b')[$ind] ?? $d->r_preg_hepab_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="r_preg_hepab_c[]" value="{{old('r_preg_hepab_c')[$ind] ?? $d->r_preg_hepab_c ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_a[]" value="{{old('nr_preg_hepab_a')[$ind] ?? $d->nr_preg_hepab_a ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_b[]" value="{{old('nr_preg_hepab_b')[$ind] ?? $d->nr_preg_hepab_b ?? 0}}" min="0" max="999" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="nr_preg_hepab_c[]" value="{{old('nr_preg_hepab_c')[$ind] ?? $d->nr_preg_hepab_c ?? 0}}" min="0" max="999" required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection