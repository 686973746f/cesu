@extends('layouts.app_pdf');

@section('content')
    <div class="container-fluid">
        <div class="text-center">
            <img src="{{asset('assets/images/oni_head.png')}}" alt="">
            <p class="font-weight-bold">SAMPLE PICK-UP FORM</p>
            <span>Name of Institute/Facility: <u>{{$details->dru}}</u></span>
            <p>Name and Number of Contact Person: <u>{{$details->contactPerson." ".$details->contactMobile}}</u></p>
        </div>
        <table class="table table-bordered">
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
                    <th style="vertical-align: middle;">TYPE OF SPECIMENT</th>
                    <th style="vertical-align: middle;">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $key=> $item)
                <tr class="text-center">
                    <td scope="row">{{$item->specNo}}</td>
                    <td>{{date('m-d-Y', strtotime($item->dateAndTimeCollected))}}</td>
                    <td>{{date('H:i', strtotime($item->dateAndTimeCollected))}}</td>
                    <td>{{$item->accessionNo}}</td>
                    <td>{{$item->records->lname}}</td>
                    <td>{{$item->records->fname}}</td>
                    <td>{{substr($item->records->fname, 0,1)}}.</td>
                    <td>{{$item->oniReferringHospital}}</td>
                    <td>{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
                    <td>{{$item->records->getAge()}}</td>
                    <td>{{substr($item->records->gender, 0, 1)}}</td>
                    <td>{{$item->oniSpecType}}</td>
                    <td>{{$item->remarks}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            <img src="{{asset('assets/images/oni_foot.png')}}" alt="">
        </div>
    </div>
@endsection