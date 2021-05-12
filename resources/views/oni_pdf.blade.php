@extends('layouts.app_pdf')
<style>
    @page { margin: 0; }
    body { margin: 0; }
</style>
@section('content')
    @php
    $n = 0;
    @endphp
    <div class="container-fluid">
        @while($n+1 <= $list->count())
        <div>
            <div class="text-center">
                <img src="{{asset('assets/images/oni_head.png')}}" alt="" style="width: 50rem;" class="mt-3">
                <h6 class="font-weight-bold">SAMPLE PICK-UP FORM</h6>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <span>Name of Institute/Facility: <u>{{$details->dru}}</u></span>
                    <p>Name and Number of Contact Person: <u>{{$details->contactPerson." ".$details->contactMobile}}</u></p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" style="font-size: 70%">
                    <thead>
                        <tr class="text-center">
                            <th style="vertical-align: middle;">ZIPLOCK NUMBER</th>
                            <th style="vertical-align: middle;">DATE COLLECTED</th>
                            <th style="vertical-align: middle;">TIME COLLECTED</th>
                            <th style="vertical-align: middle;">ACCESSION NO.</th>
                            <th style="vertical-align: middle;">SURNAME</th>
                            <th style="vertical-align: middle;">FIRSTNAME</th>
                            <th style="vertical-align: middle;">M.I</th>
                            <th style="vertical-align: middle;">REFERRING HOSPITAL</th>
                            <th style="vertical-align: middle;">DATE OF BIRTH</th>
                            <th style="vertical-align: middle;">AGE</th>
                            <th style="vertical-align: middle;">SEX</th>
                            <th style="vertical-align: middle;">TYPE OF SPECIMEN</th>
                            <th style="vertical-align: middle;">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody style="white-space: nowrap;">
                        @for($i=0;$i<=9;$i++)
                            @if($n != $list->count())
                            <tr class="text-center">
                                <td style="vertical-align: middle;">{{$list[$n]->specNo}}</td>
                                <td style="vertical-align: middle;">{{date('m/d/Y', strtotime($list[$n]->dateAndTimeCollected))}}</td>
                                <td style="vertical-align: middle;">{{date('H:i', strtotime($list[$n]->dateAndTimeCollected))}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->accessionNo}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->records->lname}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->records->fname}}</td>
                                <td style="vertical-align: middle;">{{(!is_null($list[$n]->records->mname)) ? substr($list[$n]->records->mname, 0,1) : 'N/A'}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->oniReferringHospital}}</td>
                                <td style="vertical-align: middle;">{{date('m/d/Y', strtotime($list[$n]->records->bdate))}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->records->getAge()}}</td>
                                <td style="vertical-align: middle;">{{substr($list[$n]->records->gender,0,1)}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->oniSpecType}}</td>
                                <td style="vertical-align: middle;">{{$list[$n]->remarks}}</td>
                            </tr>
                            @php
                            $n++;
                            @endphp
                            @else
                            <tr class="text-center">
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                                <td style="vertical-align: middle;">N/A</td>
                            </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <img src="{{asset('assets/images/oni_foot.png')}}" alt="" style="width: 50rem;">
            </div>
        </div>
        @if($n+1 < $list->count())
        <div class="page-break mb-3"></div>
        @endif
        @endwhile
    </div>
@endsection