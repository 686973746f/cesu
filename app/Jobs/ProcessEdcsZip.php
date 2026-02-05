<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProcessEdcsZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $zipPath;
    public ?int $userId;

    public function __construct(string $zipPath, ?int $userId = null) {
        $this->zipPath = $zipPath; // relative to storage/app
        $this->userId = $userId;
    }

    public function handle(): void {
        $absoluteZipPath = storage_path('app/' . $this->zipPath);

        // Extract folder per upload
        $extractBase = 'uploads/extracted/' . pathinfo($this->zipPath, PATHINFO_FILENAME) . '_' . uniqid();
        Storage::makeDirectory($extractBase);

        $absoluteExtractPath = storage_path('app/' . $extractBase);

        $zip = new ZipArchive();
        $opened = $zip->open($absoluteZipPath);

        if ($opened !== true) {
            throw new \RuntimeException("Failed to open ZIP: {$this->zipPath} (ZipArchive code: {$opened})");
        }

        // Extract
        if (!$zip->extractTo($absoluteExtractPath)) {
            $zip->close();
            throw new \RuntimeException("Failed to extract ZIP: {$this->zipPath}");
        }
        $zip->close();

        // Find CSV containing "dengue" in filename (case-insensitive)
        $allFiles = Storage::allFiles($extractBase);

        $dengueCsv = collect($allFiles)
            ->filter(function ($file) {
                $name = basename($file);
                return preg_match('/dengue/i', $name)
                    && preg_match('/\.csv$/i', $name);
            })
            ->values()
            ->first();

        if (!$dengueCsv) {
            // No dengue CSV found - decide what you want to do
            Log::warning("No dengue CSV found in extracted ZIP", [
                'zip' => $this->zipPath,
                'user_id' => $this->userId,
                'extract_dir' => $extractBase,
            ]);

            // Optionally: cleanup extracted folder
            // Storage::deleteDirectory($extractBase);

            return;
        }

        // At this point you have the CSV file path (relative to storage/app)
        // Example: parse/import it
        // $stream = Storage::readStream($dengueCsv);

        Log::info("Dengue CSV found", [
            'csv' => $dengueCsv,
            'zip' => $this->zipPath,
            'user_id' => $this->userId,
        ]);

        // OPTIONAL NEXT STEP (common):
        // - move it to a final folder
        // $finalPath = 'uploads/ready/dengue_' . now()->format('Ymd_His') . '_' . basename($dengueCsv);
        // Storage::move($dengueCsv, $finalPath);

        // OPTIONAL cleanup:
        // Storage::delete($this->zipPath);
        // Storage::deleteDirectory($extractBase);
    }
}
