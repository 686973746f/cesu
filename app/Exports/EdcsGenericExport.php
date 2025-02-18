<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EdcsGenericExport implements FromCollection, WithHeadings
{
    protected $case;
    protected $year;
    protected $modelInstance;
    protected $columns;
    protected $showDisabled;
    protected $showNonMatchCaseDef;

    public function __construct($case, $year, $showDisabled, $showNonMatchCaseDef)
    {
        $this->case = $case;
        $this->year = $year;
        $this->showDisabled = $showDisabled;
        $this->showNonMatchCaseDef = $showNonMatchCaseDef;
        
        // Dynamically instantiate the model
        $modelClass = "App\\Models\\$case";
        if (class_exists($modelClass)) {
            $this->modelInstance = new $modelClass;
            
            // Get the first record to determine the correct column order
            $firstRecord = $this->modelInstance::first();
            if ($firstRecord) {
                $this->columns = array_keys($firstRecord->getAttributes()); // Preserve order
            } else {
                // Fallback if no records exist
                $this->columns = Schema::getColumnListing($this->modelInstance->getTable());
            }
        } else {
            throw new \Exception("Model $modelClass does not exist.");
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $qry = "App\\Models\\$this->case"::where('Year', $this->year);

        if($this->showDisabled == 0) {
            $qry = $qry->where('enabled', 1);
        }

        if($this->showNonMatchCaseDef == 0) {
            $qry = $qry->where('match_casedef', 1);
        }

        return $qry->get()
        ->map(function ($item) {
            return collect($item)->only($this->columns); // Preserve column order
        });
    }

    /**
     * Dynamically get the column names.
     */
    public function headings(): array
    {
        return $this->columns;
    }
}
