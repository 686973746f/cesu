<?php

namespace App\Http\Controllers;

use App\Imports\VaxcertMasterlistImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class VaxcertController extends Controller
{
    public function remoteimport() {
        //Excel::import(new VaxcertMasterlistImport(), storage_path('app/vaxcert/MASTERLIST.xlsx'));

        
    }
}
