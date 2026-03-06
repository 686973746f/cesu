<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\InhouseChildCare;
use App\Models\InhouseMaternalCare;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $list = InhouseChildCare::get();

        foreach($list as $l) {
            $bdate = Carbon::parse($l->bdate_fixed);

            if($bdate->year == Carbon::parse($l->registration_date)->year - 1) {
                $lastyeardate = Carbon::createFromDate(Carbon::parse($l->bdate_fixed)->year, 12, 31);

                $l->dpt1_months_py = (!is_null($l->dpt1) && $lastyeardate->isSameYear(Carbon::parse($l->dpt1))) ? Carbon::parse($l->dpt1)->diffInMonths($lastyeardate) : null;
                $l->dpt2_months_py = (!is_null($l->dpt2) && $lastyeardate->isSameYear(Carbon::parse($l->dpt2))) ? Carbon::parse($l->dpt2)->diffInMonths($lastyeardate) : null;
                $l->dpt3_months_py = (!is_null($l->dpt3) && $lastyeardate->isSameYear(Carbon::parse($l->dpt3))) ? Carbon::parse($l->dpt3)->diffInMonths($lastyeardate) : null;
                $l->opv1_months_py = (!is_null($l->opv1) && $lastyeardate->isSameYear(Carbon::parse($l->opv1))) ? Carbon::parse($l->opv1)->diffInMonths($lastyeardate) : null;
                $l->opv2_months_py = (!is_null($l->opv2) && $lastyeardate->isSameYear(Carbon::parse($l->opv2))) ? Carbon::parse($l->opv2)->diffInMonths($lastyeardate) : null;
                $l->opv3_months_py = (!is_null($l->opv3) && $lastyeardate->isSameYear(Carbon::parse($l->opv3))) ? Carbon::parse($l->opv3)->diffInMonths($lastyeardate) : null;
                $l->ipv1_months_py = (!is_null($l->ipv1) && $lastyeardate->isSameYear(Carbon::parse($l->ipv1))) ? Carbon::parse($l->ipv1)->diffInMonths($lastyeardate) : null;
                $l->ipv2_months_py = (!is_null($l->ipv2) && $lastyeardate->isSameYear(Carbon::parse($l->ipv2))) ? Carbon::parse($l->ipv2)->diffInMonths($lastyeardate) : null;
                $l->ipv3_months_py = (!is_null($l->ipv3) && $lastyeardate->isSameYear(Carbon::parse($l->ipv3))) ? Carbon::parse($l->ipv3)->diffInMonths($lastyeardate) : null;
                $l->pcv1_months_py = (!is_null($l->pcv1) && $lastyeardate->isSameYear(Carbon::parse($l->pcv1))) ? Carbon::parse($l->pcv1)->diffInMonths($lastyeardate) : null;
                $l->pcv2_months_py = (!is_null($l->pcv2) && $lastyeardate->isSameYear(Carbon::parse($l->pcv2))) ? Carbon::parse($l->pcv2)->diffInMonths($lastyeardate) : null;
                $l->pcv3_months_py = (!is_null($l->pcv3) && $lastyeardate->isSameYear(Carbon::parse($l->pcv3))) ? Carbon::parse($l->pcv3)->diffInMonths($lastyeardate) : null;
                $l->mmr1_months_py = (!is_null($l->mmr1) && $lastyeardate->isSameYear(Carbon::parse($l->mmr1))) ? Carbon::parse($l->mmr1)->diffInMonths($lastyeardate) : null;
                $l->mmr2_months_py = (!is_null($l->mmr2) && $lastyeardate->isSameYear(Carbon::parse($l->mmr2))) ? Carbon::parse($l->mmr2)->diffInMonths($lastyeardate) : null;

                $l->save();
            }
        }
    }
}
