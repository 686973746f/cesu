<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\ExportJobs;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\PharmacyStockCard;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use OpenSpout\Common\Entity\Style\Style;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CallPharmacyDispensaryV1Export implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $task_id;
    protected $start;
    protected $end;
    protected $select_branch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $task_id, $start, $end, $select_branch)
    {
        $this->user_id = $user_id;
        $this->task_id = $task_id;
        $this->start = $start;
        $this->end = $end;
        $this->select_branch = $select_branch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $start = Carbon::parse($this->start)->startOfDay();
            $end   = Carbon::parse($this->end)->endOfDay();
            
            $q = PharmacyStockCard::query()
            ->whereBetween('created_at', [$this->start, $this->end])
            ->where('type', 'ISSUED');

            if ($this->select_branch !== 'ALL') {
                $q->whereHas('pharmacysub', fn ($qq) => $qq->where('pharmacy_branch_id', $this->select_branch));
            }

            $fileName = 'PHARMACY_MEDICINE_DISPENSARY_V1_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '_' . now()->format('His') . '.xlsx';
            $directory = storage_path('export_jobs');
            $path = $directory . DIRECTORY_SEPARATOR . $fileName;

            $rows = (function () use ($q) {
                foreach ($q->cursor() as $f) {

                    if (!is_null($f->receiving_patient_id)) {
                        $name = $f->getReceivingPatient->lname.', '.$f->getReceivingPatient->fname;
                        $age = $f->getReceivingPatient->getAge();
                        $sex = substr($f->getReceivingPatient->gender, 0, 1);
                        $barangay = $f->getReceivingPatient->address_brgy_text;
                    } else {
                        $name = $f->getReceivingBranch->name;
                        $age = 'N/A';
                        $sex = 'N/A';
                        $barangay = (!is_null($f->getReceivingBranch->if_bhs_id))
                            ? $f->getReceivingBranch->bhs->brgy->brgyName
                            : "N/A";
                    }

                    yield [
                        'DATE/TIME' => date('m/d/Y h:i A', strtotime($f->created_at)),
                        'NAME' => $name,
                        'AGE' => $age,
                        'SEX' => $sex,
                        'BARANGAY' => $barangay,
                        'MEDICINE GIVEN' => $f->pharmacysub->pharmacysupplymaster->name,
                        'QUANTITY' => $f->qty_to_process.' '.Str::plural($f->qty_type, $f->qty_to_process),
                        'ENCODER' => $f->user->name,
                    ];
                }
            })();

            $headerStyle = (new Style())->setFontBold();

            // IMPORTANT: export to a FILE (not download)
            (new FastExcel($rows))
                ->headerStyle($headerStyle)
                ->export($path);

            $job_update = ExportJobs::where('id', $this->task_id)->update([
                'status' => 'completed',
                'filename' => $fileName,
                'date_finished' => date('Y-m-d H:i:s'),
            ]);
            
        } catch (\Exception $e) {
            // Update job status to failed
            ExportJobs::where('id', $this->task_id)->update([
                'status' => 'failed',
                'date_finished' => date('Y-m-d H:i:s'),
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
