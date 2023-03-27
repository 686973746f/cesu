<?php

namespace App\Http\Controllers;

use App\Imports\VaxcertMasterlistImport;
use App\Imports\VaxcertMasterlistImportv2;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class VaxcertController extends Controller
{
    public function remoteimport() {
        Excel::import(new VaxcertMasterlistImport(), storage_path('app/vaxcert/masterlistv2.xlsx'));

        /*
        $import = new VaxcertMasterlistImportv2();
    
        Excel::filter('chunk')->load(storage_path('app/vaxcert/masterlistv2.csv'))->chunk(1000, function($results) use ($import) {
            $import->onRow($results);
        });
        */
    }
}
