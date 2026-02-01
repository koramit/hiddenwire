<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-files';

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
        $paths = ['l/c/25l', 'l/c/24l'];
        foreach ($paths as $path) {
            $files = Storage::allFiles($path);
            foreach ($files as $file) {
                $filename = implode('.', [
                    pathinfo($file, PATHINFO_FILENAME),
                    pathinfo($file, PATHINFO_EXTENSION)
                ]);
                $this->line("$filename");
                $attachment = Attachment::query()->where('filename', $filename)->first();
                if (!$attachment) {
                    continue;
                }

                $to = 'l/c/' . $attachment->created_at->tz('Asia/Bangkok')->format('y/m/d') . '/' . $filename;
                $this->line("$file -> $filename");
            }
        }
    }
}
