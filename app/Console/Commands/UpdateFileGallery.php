<?php

namespace App\Console\Commands;

use App\Models\Gallery;
use Illuminate\Console\Command;

class UpdateFileGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update file column in gallery table';

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
        $galeries = Gallery::all();
        foreach ($galeries as $gallery) {
            $gallery->file = $gallery->name;
            $gallery->name = explode('/', $gallery->name)[1];
            $gallery->save();
        }

        return Command::SUCCESS;
    }
}
