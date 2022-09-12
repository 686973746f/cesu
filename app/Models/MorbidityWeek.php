<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MorbidityWeek extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'year',
        'mw1',
        'mw2',
        'mw3',
        'mw4',
        'mw5',
        'mw6',
        'mw7',
        'mw8',
        'mw9',
        'mw10',
        'mw11',
        'mw12',
        'mw13',
        'mw14',
        'mw15',
        'mw16',
        'mw17',
        'mw18',
        'mw19',
        'mw20',
        'mw21',
        'mw22',
        'mw23',
        'mw24',
        'mw25',
        'mw26',
        'mw27',
        'mw28',
        'mw29',
        'mw30',
        'mw31',
        'mw32',
        'mw33',
        'mw34',
        'mw35',
        'mw36',
        'mw37',
        'mw38',
        'mw39',
        'mw40',
        'mw41',
        'mw42',
        'mw43',
        'mw44',
        'mw45',
        'mw46',
        'mw47',
        'mw48',
        'mw49',
        'mw50',
        'mw51',
        'mw52',
        'mw53',
    ];

    public function ifmw52or53firtdaysofmonth() {
        if(date('W') == 52 || date('W') == 53) {
            if(date('m') == 1) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}
