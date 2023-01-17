@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
    <div class="card">
        <div class="card-header">Monthly Report</div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group">
                    <select class="custom-select" name="sy" id="sy" required>
                        @foreach(range(date('Y'), 2021) as $y)
                        <option value="{{$y}}">{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">Animal Bite Cases ({{$sy}})</th>
                            <th scope="col">JAN</th>
                            <th scope="col">FEB</th>
                            <th scope="col">MAR</th>
                            <th scope="col">APR</th>
                            <th scope="col">MAY</th>
                            <th scope="col">JUN</th>
                            <th scope="col">JUL</th>
                            <th scope="col">AUG</th>
                            <th scope="col">SEP</th>
                            <th scope="col">OCT</th>
                            <th scope="col">NOV</th>
                            <th scope="col">DEC</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr class="">
                            <td>Male</td>
                            <td>{{$m1}}</td>
                            <td>{{$m2}}</td>
                            <td>{{$m3}}</td>
                            <td>{{$m4}}</td>
                            <td>{{$m5}}</td>
                            <td>{{$m6}}</td>
                            <td>{{$m7}}</td>
                            <td>{{$m8}}</td>
                            <td>{{$m9}}</td>
                            <td>{{$m10}}</td>
                            <td>{{$m11}}</td>
                            <td>{{$m12}}</td>
                            <td>{{$m1 + $m2 + $m3 + $m4 + $m5 + $m6 + $m7 + $m8 + $m9 + $m10 + $m11 + $m12}}</td>
                        </tr>
                        <tr class="">
                            <td>Female</td>
                            <td>{{$f1}}</td>
                            <td>{{$f2}}</td>
                            <td>{{$f3}}</td>
                            <td>{{$f4}}</td>
                            <td>{{$f5}}</td>
                            <td>{{$f6}}</td>
                            <td>{{$f7}}</td>
                            <td>{{$f8}}</td>
                            <td>{{$f9}}</td>
                            <td>{{$f10}}</td>
                            <td>{{$f11}}</td>
                            <td>{{$f12}}</td>
                            <td>{{$f1 + $f2 + $f3 + $f4 + $f5 + $f6 + $f7 + $f8 + $f9 + $f10 + $f11 + $f12}}</td>
                        </tr>
                        <tr class="bg-light">
                            <td><b>TOTAL</b></td>
                            <td>{{$m1 + $f1}}</td>
                            <td>{{$m2 + $f2}}</td>
                            <td>{{$m3 + $f3}}</td>
                            <td>{{$m4 + $f4}}</td>
                            <td>{{$m5 + $f5}}</td>
                            <td>{{$m6 + $f6}}</td>
                            <td>{{$m7 + $f7}}</td>
                            <td>{{$m8 + $f8}}</td>
                            <td>{{$m9 + $f9}}</td>
                            <td>{{$m10 + $f10}}</td>
                            <td>{{$m11 + $f11}}</td>
                            <td>{{$m12 + $f12}}</td>
                            <td>{{$m1 + $m2 + $m3 + $m4 + $m5 + $m6 + $m7 + $m8 + $m9 + $m10 + $m11 + $m12 + $f1 + $f2 + $f3 + $f4 + $f5 + $f6 + $f7 + $f8 + $f9 + $f10 + $f11 + $f12}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="">
                            <td>> 18</td>
                            <td>{{$oe1}}</td>
                            <td>{{$oe2}}</td>
                            <td>{{$oe3}}</td>
                            <td>{{$oe4}}</td>
                            <td>{{$oe5}}</td>
                            <td>{{$oe6}}</td>
                            <td>{{$oe7}}</td>
                            <td>{{$oe8}}</td>
                            <td>{{$oe9}}</td>
                            <td>{{$oe10}}</td>
                            <td>{{$oe11}}</td>
                            <td>{{$oe12}}</td>
                            <td>{{$oe1 + $oe2 + $oe3 + $oe4 + $oe5 + $oe6 + $oe7 + $oe8 + $oe9 + $oe10 + $oe11 + $oe12}}</td>
                        </tr>
                        <tr class="">
                            <td>< 18</td>
                            <td>{{$ue1}}</td>
                            <td>{{$ue2}}</td>
                            <td>{{$ue3}}</td>
                            <td>{{$ue4}}</td>
                            <td>{{$ue5}}</td>
                            <td>{{$ue6}}</td>
                            <td>{{$ue7}}</td>
                            <td>{{$ue8}}</td>
                            <td>{{$ue9}}</td>
                            <td>{{$ue10}}</td>
                            <td>{{$ue11}}</td>
                            <td>{{$ue12}}</td>
                            <td>{{$ue1 + $ue2 + $ue3 + $ue4 + $ue5 + $ue6 + $ue7 + $ue8 + $ue9 + $ue10 + $ue11 + $ue12}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="">
                            <td>CAT 1</td>
                            <td>{{$co1}}</td>
                            <td>{{$co2}}</td>
                            <td>{{$co3}}</td>
                            <td>{{$co4}}</td>
                            <td>{{$co5}}</td>
                            <td>{{$co6}}</td>
                            <td>{{$co7}}</td>
                            <td>{{$co8}}</td>
                            <td>{{$co9}}</td>
                            <td>{{$co10}}</td>
                            <td>{{$co11}}</td>
                            <td>{{$co12}}</td>
                            <td>{{$co1 + $co2 + $co3 + $co4 + $co5 + $co6 + $co7 + $co8 + $co9 + $co10 + $co11 + $co12}}</td>
                        </tr>
                        <tr class="">
                            <td>CAT 2</td>
                            <td>{{$ct1}}</td>
                            <td>{{$ct2}}</td>
                            <td>{{$ct3}}</td>
                            <td>{{$ct4}}</td>
                            <td>{{$ct5}}</td>
                            <td>{{$ct6}}</td>
                            <td>{{$ct7}}</td>
                            <td>{{$ct8}}</td>
                            <td>{{$ct9}}</td>
                            <td>{{$ct10}}</td>
                            <td>{{$ct11}}</td>
                            <td>{{$ct12}}</td>
                            <td>{{$ct1 + $ct2 + $ct3 + $ct4 + $ct5 + $ct6 + $ct7 + $ct8 + $ct9 + $ct10 + $ct11 + $ct12}}</td>
                        </tr>
                        <tr class="">
                            <td>CAT 3</td>
                            <td>{{$ch1}}</td>
                            <td>{{$ch2}}</td>
                            <td>{{$ch3}}</td>
                            <td>{{$ch4}}</td>
                            <td>{{$ch5}}</td>
                            <td>{{$ch6}}</td>
                            <td>{{$ch7}}</td>
                            <td>{{$ch8}}</td>
                            <td>{{$ch9}}</td>
                            <td>{{$ch10}}</td>
                            <td>{{$ch11}}</td>
                            <td>{{$ch12}}</td>
                            <td>{{$ch1 + $ch2 + $ch3 + $ch4 + $ch5 + $ch6 + $ch7 + $ch8 + $ch9 + $ch10 + $ch11 + $ch12}}</td>
                        </tr>
                        <tr class="bg-light">
                            <td><b>TOTAL</b></td>
                            <td>{{$co1 + $ct1 + $ch1}}</td>
                            <td>{{$co2 + $ct2 + $ch2}}</td>
                            <td>{{$co3 + $ct3 + $ch3}}</td>
                            <td>{{$co4 + $ct4 + $ch4}}</td>
                            <td>{{$co5 + $ct5 + $ch5}}</td>
                            <td>{{$co6 + $ct6 + $ch6}}</td>
                            <td>{{$co7 + $ct7 + $ch7}}</td>
                            <td>{{$co8 + $ct8 + $ch8}}</td>
                            <td>{{$co9 + $ct9 + $ch9}}</td>
                            <td>{{$co10 + $ct10 + $ch10}}</td>
                            <td>{{$co11 + $ct11 + $ch11}}</td>
                            <td>{{$co12 + $ct12 + $ch12}}</td>
                            <td>{{$co1 + $co2 + $co3 + $co4 + $co5 + $co6 + $co7 + $co8 + $co9 + $co10 + $co11 + $co12 + $ch1 + $ch2 + $ch3 + $ch4 + $ch5 + $ch6 + $ch7 + $ch8 + $ch9 + $ch10 + $ch11 + $ch12 + $ch1 + $ch2 + $ch3 + $ch4 + $ch5 + $ch6 + $ch7 + $ch8 + $ch9 + $ch10 + $ch11 + $ch12}}</td>
                        </tr>
                        <tr class="">
                            <td>DOG</td>
                            <td>{{$dog1}}</td>
                            <td>{{$dog2}}</td>
                            <td>{{$dog3}}</td>
                            <td>{{$dog4}}</td>
                            <td>{{$dog5}}</td>
                            <td>{{$dog6}}</td>
                            <td>{{$dog7}}</td>
                            <td>{{$dog8}}</td>
                            <td>{{$dog9}}</td>
                            <td>{{$dog10}}</td>
                            <td>{{$dog11}}</td>
                            <td>{{$dog12}}</td>
                            <td>{{$dog1 + $dog2 + $dog3 + $dog4 + $dog5 + $dog6 + $dog7 + $dog8 + $dog9 + $dog10 + $dog11 + $dog12}}</td>
                        </tr>
                        <tr class="">
                            <td>CAT</td>
                            <td>{{$cat1}}</td>
                            <td>{{$cat2}}</td>
                            <td>{{$cat3}}</td>
                            <td>{{$cat4}}</td>
                            <td>{{$cat5}}</td>
                            <td>{{$cat6}}</td>
                            <td>{{$cat7}}</td>
                            <td>{{$cat8}}</td>
                            <td>{{$cat9}}</td>
                            <td>{{$cat10}}</td>
                            <td>{{$cat11}}</td>
                            <td>{{$cat12}}</td>
                            <td>{{$cat1 + $cat2 + $cat3 + $cat4 + $cat5 + $cat6 + $cat7 + $cat8 + $cat9 + $cat10 + $cat11 + $cat12}}</td>
                        </tr>
                        <tr class="">
                            <td>ERIG</td>
                            <td>{{$er1}}</td>
                            <td>{{$er2}}</td>
                            <td>{{$er3}}</td>
                            <td>{{$er4}}</td>
                            <td>{{$er5}}</td>
                            <td>{{$er6}}</td>
                            <td>{{$er7}}</td>
                            <td>{{$er8}}</td>
                            <td>{{$er9}}</td>
                            <td>{{$er10}}</td>
                            <td>{{$er11}}</td>
                            <td>{{$er12}}</td>
                            <td>{{$er1 + $er2 + $er3 + $er4 + $er5 + $er6 + $er7 + $er8 + $er9 + $er10 + $er11 + $er12}}</td>
                        </tr>
                        <tr class="">
                            <td>COMPLETE</td>
                            <td>{{$oc1}}</td>
                            <td>{{$oc2}}</td>
                            <td>{{$oc3}}</td>
                            <td>{{$oc4}}</td>
                            <td>{{$oc5}}</td>
                            <td>{{$oc6}}</td>
                            <td>{{$oc7}}</td>
                            <td>{{$oc8}}</td>
                            <td>{{$oc9}}</td>
                            <td>{{$oc10}}</td>
                            <td>{{$oc11}}</td>
                            <td>{{$oc12}}</td>
                            <td>{{$oc1 + $oc2 + $oc3 + $oc4 + $oc5 + $oc6 + $oc7 + $oc8 + $oc9 + $oc10 + $oc11 + $oc12}}</td>
                        </tr>
                        <tr class="">
                            <td>INC</td>
                            <td>{{$oi1}}</td>
                            <td>{{$oi2}}</td>
                            <td>{{$oi3}}</td>
                            <td>{{$oi4}}</td>
                            <td>{{$oi5}}</td>
                            <td>{{$oi6}}</td>
                            <td>{{$oi7}}</td>
                            <td>{{$oi8}}</td>
                            <td>{{$oi9}}</td>
                            <td>{{$oi10}}</td>
                            <td>{{$oi11}}</td>
                            <td>{{$oi12}}</td>
                            <td>{{$oi1 + $oi2 + $oi3 + $oi4 + $oi5 + $oi6 + $oi7 + $oi8 + $oi9 + $oi10 + $oi11 + $oi12}}</td>
                        </tr>
                        <tr class="">
                            <td>BOOSTER</td>
                            <td>{{$bo1}}</td>
                            <td>{{$bo2}}</td>
                            <td>{{$bo3}}</td>
                            <td>{{$bo4}}</td>
                            <td>{{$bo5}}</td>
                            <td>{{$bo6}}</td>
                            <td>{{$bo7}}</td>
                            <td>{{$bo8}}</td>
                            <td>{{$bo9}}</td>
                            <td>{{$bo10}}</td>
                            <td>{{$bo11}}</td>
                            <td>{{$bo12}}</td>
                            <td>{{$bo1 + $bo2 + $bo3 + $bo4 + $bo5 + $bo6 + $bo7 + $bo8 + $bo9 + $bo10 + $bo11 + $bo12}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection