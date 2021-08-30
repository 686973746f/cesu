<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use IlluminateAgnostic\Collection\Support\Str;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:everyminute';

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
        $user = User::find(1);

        $user->name = Str::random(6);

        $user->save();
    }
}
