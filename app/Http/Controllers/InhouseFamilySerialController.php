<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use Illuminate\Http\Request;
use App\Models\InhouseFamilySerial;

class InhouseFamilySerialController extends Controller
{
    public function generateHouseholdNo(Request $request) {
        // next id based on latest record id
        $nextId = (int) (InhouseFamilySerial::max('id') ?? 0) + 1;

        if(auth()->user()->itr_facility_id == 39708) {
            //Super Health Center kasi wala pa syang official facility id
            $f = DohFacility::findOrFail(10886);
        }
        else {
            $f = auth()->user()->opdfacility;
        }

        $paddedId = str_pad($nextId, 5, '0', STR_PAD_LEFT);
        $prefix = now()->format('Ym'); // YYYY-MM

        $householdno = "{$prefix}-DOH{$f->healthfacility_code_short}-{$paddedId}";

        return response()->json([
            'householdno' => $householdno
        ]);
    }

    public function generateFamilySerialNo(Request $request) {
        // next id based on latest record id
        $nextId = (int) (InhouseFamilySerial::max('id') ?? 0) + 1;

        if(auth()->user()->itr_facility_id == 39708) {
            //Super Health Center kasi wala pa syang official facility id
            $f = DohFacility::findOrFail(10886);
        }
        else {
            $f = auth()->user()->opdfacility;
        }

        $paddedId = str_pad($nextId, 10, '0', STR_PAD_LEFT);

        $familyserialno = "DOH{$f->healthfacility_code_short}-{$paddedId}";

        return response()->json([
            'familyserialno' => $familyserialno
        ]);
    }

    public function searchFamilySerial(Request $request) {
        $q = trim($request->q);

        $rows = InhouseFamilySerial::with('patient')
            ->when($q, function ($query) use ($q) {

                $query->where('inhouse_householdno', 'like', "%{$q}%")

                ->orWhereHas('patient', function ($p) use ($q) {
                    $p->where('lname', 'like', "%{$q}%")
                    ->orWhere('fname', 'like', "%{$q}%")
                    ->orWhere('mname', 'like', "%{$q}%");
                });

            })
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();

        return response()->json($rows);
    }
}
