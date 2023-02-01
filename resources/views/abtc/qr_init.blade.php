@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-center">
            <div class="card-header">
                <p><b>CHO GENERAL TRIAS</b></p>
                <p><b>ANIMAL BITE TREATMENT CENTER (ABTC)</b></p>
                <p><b>QR VERIFICATION SYSTEM</b></p>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img src="{{asset('assets/images/cho_icon_large.png')}}" class="mb-3" style="width: 8rem;">
                    <hr>
                    <span>Beware of fake verification sites. The legitimate site should have this domain name <code>https://cesugentri.com/abtc/qr/</code></span>
                </div>
                <hr>
                @if($found != 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Registration #</td>
                                <td>{{$b->case_id}}</td>
                            </tr>
                            <tr>
                                <td>Registration Date</td>
                                <td>{{date('m/d/Y', strtotime($b->case_date))}}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{substr($b->patient->lname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->lname, 1))}}, {{substr($b->patient->fname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->fname, 1))}} {{(!is_null($b->patient->mname)) ? substr($b->patient->mname, 0, 1) . preg_replace('/[^@]/', '*', substr($b->patient->mname, 1)) : ''}} </td>
                            </tr>
                            <tr>
                                <td>Exposure Date</td>
                                <td>{{date('m/d/Y', strtotime($b->bite_date))}}</td>
                            </tr>
                            <tr>
                                <td>Category Level</td>
                                <td>{{$b->category_level}}</td>
                            </tr>
                            <tr>
                                <td>Brand Name</td>
                                <td>{{$b->brand_name}}</td>
                            </tr>
                            <tr>
                                <td>Day 0 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d0_date))}} - {{($b->d0_done == 1) ? 'DONE' : 'PENDING'}}</td>
                            </tr>
                            <tr>
                                <td>Day 3 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d3_date))}} - {{($b->d3_done == 1) ? 'DONE' : 'PENDING'}}</td>
                            </tr>
                            @if($b->is_booster != 1)
                            <tr>
                                <td>Day 7 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d7_date))}} - {{($b->d7_done == 1) ? 'DONE' : 'PENDING'}}</td>
                            </tr>
                            @if($b->pep_route == 'IM')
                            <tr>
                                <td>Day 14 / Status</td>
                                <td>{{date('m/d/Y', strtotime($b->d14_date))}} - {{($b->d14_done == 1) ? 'DONE' : 'PENDING'}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <div>Day 28 / Status</div>
                                    <div><small>(If Animal Died/Lost)</small></div>
                                </td>
                                <td>{{date('m/d/Y', strtotime($b->d28_date))}} - {{($b->d28_done == 1) ? 'DONE' : ''}}</td>
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