<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class MoveAttachment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-attachment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Moving attachment...');
        $allFiles = Storage::allFiles("l/c");
        $allFilesCount = count($allFiles);
        $this->line("$allFilesCount files");

        foreach ($allFiles as $file) {
            if (str_starts_with($file, "l/c/2")) {
                continue;
            }

            $timestamp = Storage::lastModified($file);
            $path = Carbon::createFromTimestamp($timestamp)
                ->tz('Asia/Bangkok')
                ->format("y/m/d/");

            $to = "l/c/" . $path . $file;

            Storage::move($file, $to);

            $this->info("$file => $to");
        }
    }
}
