<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            @media print{@page {size: landscape}}

            @page { margin: 0; }
            body { margin: 0; }
        </style>
    </head>
    <body style="background-color: white;">
        @php
        $n = 0;
        if($size == 'legal') {
            $fsize = '70%';
            $picw = '50rem';
            $tblmarginbottom = '1';
        }
        else {
            $fsize = '70%';
            $picw = '40rem';
            $tblmarginbottom = '3';
        }
        @endphp
        @while($n+1 <= $list->count())
        <div class="container-fluid my-0" style="font-family: Arial, Helvetica, sans-serif; page-break-after: {{($n+11 <= $list->count()) ? 'always' : 'avoid'}};">
            <div class="text-center">
                <img src="{{asset('assets/images/oni_head.png')}}" alt="" style="width: {{$picw}};" class="mt-3 mb-0">
                <h6 class="font-weight-bold my-0">SAMPLE PICK-UP FORM</h6>
            </div>
            <span>Name of Institute/Facility: <u>{{$details->dru}}</u></span>
            <p class="mb-1">Name and Number of Contact Person: <u>{{$details->contactPerson." ".$details->contactMobile}}</u></p>
            <div class="table-responsive">
                <table class="table table-bordered mb-{{$tblmarginbottom}}" style="font-size: {{$fsize}};">
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
                                <td style="vertical-align: middle;">{{($list[$n]->specNo <= 9) ? '0'.$list[$n]->specNo : $list[$n]->specNo}}</td>
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
            <div class="text-center mb-0">
                <img class="mt-0 mb-0 text-center" src="{{asset('assets/images/oni_foot.png')}}" alt="" style="width: {{$picw}};">
            </div>
        </div>
        @endwhile
    <body>
</html>