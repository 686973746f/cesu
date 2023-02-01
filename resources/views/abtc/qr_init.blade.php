@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-center">
            <div class="card-header">
                <div><b>CHO GENERAL TRIAS</b></div>
                <div><b>ANIMAL BITE TREATMENT CENTER (ABTC)</b></div>
                <div><b>QR VERIFICATION SYSTEM</b></div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div><img src="{{asset('assets/images/cho_icon_large.png')}}" class="mb-3" style="width: 8rem;"></div>
                    <span>Beware of fake verification sites. The legitimate site should have this domain name <code>https://cesugentri.com/abtc/qr/</code></span>
                </div>
                <hr>
                @if($found != 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-light">Registration #</td>
                                <td>{{$b->case_id}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Registration Date</td>
                                <td>{{date('m/d/Y', strtotime($b->case_date))}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Name</td>
                                <td>{{substr($b->patient->lname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->lname, 1))}}, {{substr($b->patient->fname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->fname, 1))}} {{(!is_null($b->patient->mname)) ? substr($b->patient->mname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->mname, 1)) : ''}} </td>
                            </tr>
                            <tr>
                                <td class="bg-light">Exposure Date</td>
                                <td>{{date('m/d/Y', strtotime($b->bite_date))}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Exposure Type</td>
                                <td>{{$b->getBiteType()}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Category Level</td>
                                <td>{{$b->category_level}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Brand Name</td>
                                <td>{{$b->brand_name}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Day 0 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d0_date))}} - <b class="{{($b->d0_done == 1) ? 'text-success' : 'text-warning'}}">{{($b->d0_done == 1) ? 'DONE' : 'PENDING'}}</b></td>
                            </tr>
                            <tr>
                                <td class="bg-light">Day 3 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d3_date))}} - <b class="{{($b->d3_done == 1) ? 'text-success' : 'text-warning'}}">{{($b->d3_done == 1) ? 'DONE' : 'PENDING'}}</b></td>
                            </tr>
                            @if($b->is_booster != 1)
                            <tr>
                                <td class="bg-light">Day 7 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d7_date))}} - <b class="{{($b->d7_done == 1) ? 'text-success' : 'text-warning'}}">{{($b->d7_done == 1) ? 'DONE' : 'PENDING'}}</b></td>
                            </tr>
                            @if($b->pep_route == 'IM')
                            <tr>
                                <td class="bg-light">Day 14 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d14_date))}} - <b class="{{($b->d14_done == 1) ? 'text-success' : 'text-warning'}}">{{($b->d14_done == 1) ? 'DONE' : 'PENDING'}}</b></td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <div>Day 28 / Status</div>
                                    <div><small>(If Animal Died/Lost)</small></div>
                                </td>
                                <td>{{date('m/d/Y', strtotime($b->d28_date))}} - <b class="{{($b->d28_done == 1) ? 'text-success' : 'text-warning'}}">{{($b->d28_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            @endif
                            <tr>
                                <td>Outcome</td>
                                <td>{{$b->outcome}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <h3 class="text-warning">INVALID QR CODE</h3>
                <p>Sorry, your QR Code is invalid.</p>
                @endif
            </div>
        </div>
        <p class="mt-3 text-center text-muted">CESU/ABTC System Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>
@endsection