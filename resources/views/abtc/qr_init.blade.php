@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <p><b>CHO GENERAL TRIAS</b></p>
                <p><b>ANIMAL BITE TREATMENT CENTER (ABTC)</b></p>
                <p><b>QR VERIFICATION SYSTEM</b></p>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <span>Beware of fake verification sites. The legitimate site should have this domain name https://cesugentri.com/abtc/qr/</span>
                </div>
                @if($found != 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Registration #</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td></td>
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