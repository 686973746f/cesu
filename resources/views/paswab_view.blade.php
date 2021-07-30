@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header font-weight-bold">Pa-Swab List @if(!request()->input('q'))(Total: {{$list->count()}})@endif</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('paswab.view')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Name / Schedule Code / Referral Code">
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(request()->input('q'))
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$list->count()}} {{Str::plural('result', $list->count())}}. <a href="{{route('paswab.view')}}">GO BACK</a>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="paswabtbl">
                        <thead class="text-center thead-light">
                            <tr>
                                <th style="vertical-align: middle;">Date Submitted</th>
                                <th style="vertical-align: middle;">Name</th>
                                <th style="vertical-align: middle;">Philhealth</th>
                                <th style="vertical-align: middle;">Mobile</th>
                                <th style="vertical-align: middle;">Birthdate</th>
                                <th style="vertical-align: middle;">Age / Gender</th>
                                <th style="vertical-align: middle;">Pregnant / LMP</th>
                                <th style="vertical-align: middle;">Client Type</th>
                                <th style="vertical-align: middle;">For Hospitalization</th>
                                <th style="vertical-align: middle;">For Antigen</th>
                                <th style="vertical-align: middle;">Vaccinated</th>
                                <th style="vertical-align: middle;">Have Symptoms</th>
                                <th style="vertical-align: middle;">Date Onset of Illness</th>
                                <th style="vertical-align: middle;">Date Interviewed</th>
                                <th style="vertical-align: middle;">Address</th>
                                <th style="vertical-align: middle;">New Record</th>
                                <th style="vertical-align: middle;">Referral Code</th>
                                <th style="vertical-align: middle;">Schedule Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                            @php
                            if(!is_null($item->vaccinationDate1)) {
                                if(!is_null($item->vaccinationDate2)) {
                                    $vaccineDose = '2nd Dose';
                                }
                                else {
                                    $vaccineDose = '1st Dose';
                                }
                            }
                            else {
                                $vaccineDose = NULL;
                            }
                            @endphp
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;"><small>{{date('m/d/Y h:i:s A', strtotime($item->created_at))}}</small></td>
                                    <td style="vertical-align: middle;"><a href="/forms/paswab/view/{{$item->id}}" class="btn btn-link text-left">{{$item->getName()}}</a></td>
                                    <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->philhealth)) ? $item->philhealth : 'N/A'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->mobile}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->bdate))}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->getAge()." / ".substr($item->gender,0,1)}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{($item->isPregnant == 1) ? 'YES / '.date('m/d/Y', strtotime($item->ifPregnantLMP)).' - '.$item->diff4Humans($item->ifPregnantLMP) : 'NO'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->getPatientType()}} <small>{{(!is_null($item->expoDateLastCont) && $item->pType == 'CLOSE CONTACT') ? "(".date('m/d/Y - D', strtotime($item->expoDateLastCont)).", ".$item->diff4Humans($item->expoDateLastCont).")" : ''}}</small></td>
                                    <td class="text-center" style="vertical-align: middle;">{{($item->isForHospitalization == 1) ? 'YES' : 'NO'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{($item->forAntigen == 1) ? 'YES' : 'NO'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->vaccinationDate1)) ? 'YES ('.$item->vaccinationName1.') - ' : 'NO'}}{{$vaccineDose}}</td>
                                    <td class="text-center {{!is_null($item->SAS) ? 'text-danger font-weight-bold' : ''}}" style="vertical-align: middle;">{{!is_null($item->SAS) ? 'YES' : 'NONE'}}</td>
                                    <td class="text-center {{(!is_null($item->dateOnsetOfIllness)) ? 'text-danger font-weight-bold' : ''}}" style="vertical-align: middle;">{{(!is_null($item->dateOnsetOfIllness)) ? date('m/d/Y (D)', strtotime($item->dateOnsetOfIllness)).' - '.$item->diff4Humans($item->dateOnsetOfIllness) : 'N/A'}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->interviewDate))}}</td>
                                    <td style="vertical-align: middle;"><small>{{$item->getAddress()}}</small></td>
                                    <td class="text-center font-weight-bold {{($item->isNewRecord == 1) ? 'text-success' : 'text-secondary'}}" style="vertical-align: middle;">{{($item->isNewRecord == 1) ? 'NEW' : 'OLD'}}</td>
                                    <td class="text-center" style="vertical-align: middle;"><small>{{(!is_null($item->linkCode)) ? $item->linkCode : 'N/A'}}</small></td>
                                    <td class="text-center" style="vertical-align: middle;">{{$item->majikCode}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#paswabtbl').dataTable({
            dom: 'tr',
            responsive: true,
            "ordering": false,
        });
    </script>
@endsection