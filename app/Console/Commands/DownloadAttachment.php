<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DownloadAttachment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-attachment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Downloading attachments...");
        $allFiles = Storage::allFiles("l/c");
        $allFilesCount = count($allFiles);
        $count = 0;
        $this->line("$allFilesCount files");

        foreach ($allFiles as $file) {
            $timestamp = Storage::lastModified($file);
            $prefix = (int) Carbon::createFromTimestamp($timestamp)
                ->tz(7)
                ->format("YmdHi");

            if ($prefix >= 202412300000 && $prefix < 202601010000 && str_contains($file, ".pdf")) {
                // check if file already exists
                if (Storage::disk("local")->exists("loaded/" . $prefix . "-" . str_replace("l/c/", "", $file))) {
                    $this->line("Skipped " . $file);
                } else {
                    Storage::disk("local")->put(
                        "loaded/" . $prefix . "-" . str_replace("l/c/", "", $file),
                        Storage::get($file)
                    );
                    $count++;
                    $this->info("Downloaded " . $file. " (" . $count . "/" . $allFilesCount . ")");
                }
            } else {
                $this->warn("Skipped " . $file);
            }

        }

        $this->info("Done");
    }
}
