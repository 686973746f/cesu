<?php

namespace App\Imports;

use App\Models\PharmacyStockCard;
use App\Models\PharmacySupplyMaster;
use App\Models\PharmacySupplySub;
use App\Models\PharmacySupplySubStock;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PharmacyExcelImport implements OnEachRow, WithHeadingRow
{
    private function transformDateTime($value, string $format = 'Y-m-d')
    {
        if(!is_null($value) && $value != 'N/A') {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            } catch (\ErrorException $e) {
                if(strtotime($value)) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
            }
        }
    }
    
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $check = PharmacySupplyMaster::where('name', $row['name'])
        ->orWhere('sku_code', $row['sku_code'])
        ->first();

        /*
        array:7 [▼
        "name" => "ACETYLCYSTEINE 600MG"
        "sku_code" => "G10001"
        "category" => "OTHERS"
        "qty_type_boxpiece" => "BOX"
        "if_box_how_many_qty_inside_per_box" => null
        "current_qty_in_stock" => "36"
        "expiration_date" => null
        ]
        */

        if(!($check)) {
            $new1 = PharmacySupplyMaster::create([
                'name' => $row['name'],
                'sku_code' => $row['sku_code'],
                'category' => $row['category'],
                'quantity_type' => $row['qty_type_boxpiece'],
                'config_piecePerBox' => $row['if_box_how_many_qty_inside_per_box'],

                'created_by' => 58,
            ]);

            $new2 = PharmacySupplySub::create([
                'supply_master_id' => $new1->id,
                'pharmacy_branch_id' => 1,

                'master_box_stock' => ($new1->quantity_type == 'BOX') ? $row['current_qty_in_stock'] : NULL,
                'master_piece_stock' => ($new1->quantity_type == 'BOX') ? ($row['current_qty_in_stock'] * $row['if_box_how_many_qty_inside_per_box']) : $row['current_qty_in_stock'],

                'created_by' => 58,
            ]);

            if($row['current_qty_in_stock'] != 0) {
                $new3 = PharmacySupplySubStock::create([
                    'subsupply_id' => $new2->id,
                    'expiration_date' => $this->transformDateTime($row['expiration_date']),
                    'current_box_stock' => ($new1->quantity_type == 'BOX') ? $row['current_qty_in_stock'] : NULL,
                    'current_piece_stock' => ($new1->quantity_type == 'BOX') ? ($row['current_qty_in_stock'] * $row['if_box_how_many_qty_inside_per_box']) : $row['current_qty_in_stock'],

                    'created_by' => 58,
                ]);

                $new4 = PharmacyStockCard::create([
                    'subsupply_id' => $new2->id,
                    'type' => 'RECEIVED',
                    'before_qty_box' => ($new1->quantity_type == 'BOX') ? 0 : NULL,
                    'before_qty_piece' => 0,
                    'qty_to_process' => $row['current_qty_in_stock'],
                    'qty_type' => $new1->quantity_type,
                    'after_qty_box' => ($new1->quantity_type == 'BOX') ? $row['current_qty_in_stock'] : NULL,
                    'after_qty_piece' => ($new1->quantity_type == 'BOX') ? ($row['current_qty_in_stock'] * $row['if_box_how_many_qty_inside_per_box']) : $row['current_qty_in_stock'],
                    'remarks' => 'INITIAL IMPORTING',

                    'created_by' => 58,
                ]);
            }      
        }
        else {
            //find subsupply
            $check2 = PharmacySupplySub::where('supply_master_id', $check->id)
            ->where('pharmacy_branch_id', 1)
            ->first();

            if($check2 && $row['current_qty_in_stock'] != 0) {
                $new3 = PharmacySupplySubStock::create([
                    'subsupply_id' => $check2->id,
                    'expiration_date' => $this->transformDateTime($row['expiration_date']),
                    'current_box_stock' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? $row['current_qty_in_stock'] : NULL,
                    'current_piece_stock' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? ($row['current_qty_in_stock'] * $row['if_box_how_many_qty_inside_per_box']) : $row['current_qty_in_stock'],

                    'created_by' => 58,
                ]);

                $new4 = PharmacyStockCard::create([
                    'subsupply_id' => $check2->id,
                    'type' => 'RECEIVED',
                    'before_qty_box' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? 0 : NULL,
                    'before_qty_piece' => 0,
                    'qty_to_process' => $row['current_qty_in_stock'],
                    'qty_type' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? 'BOX' : 'PIECE',
                    'after_qty_box' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? $row['current_qty_in_stock'] : NULL,
                    'after_qty_piece' => ($check2->pharmacysupplymaster->quantity_type == 'BOX') ? ($row['current_qty_in_stock'] * $row['if_box_how_many_qty_inside_per_box']) : $row['current_qty_in_stock'],
                    'remarks' => 'INITIAL IMPORTING',

                    'created_by' => 58,
                ]);
            }
        }
    }
}
