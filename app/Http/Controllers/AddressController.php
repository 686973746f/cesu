<?php

namespace App\Http\Controllers;

use App\Models\EdcsBrgy;
use App\Models\EdcsCity;
use App\Models\EdcsProvince;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function testAddress() {
        return view('test_address');
    }

    public function getProvinces($region_id) {
        $list = EdcsProvince::where('region_id', $region_id)->pluck('name', 'id');

        return response()->json($list);
    }

    public function getCityMun($province_id) {
        $list = EdcsCity::where('province_id', $province_id)->pluck('name', 'id');

        return response()->json($list);
    }

    public function getBrgy($citymun_id) {
        $list = EdcsBrgy::where('city_id', $citymun_id)->pluck('name', 'id');
        
        return response()->json($list);
    }
}
