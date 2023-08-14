<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FollowUp;

class UpdateMonthlyDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDates:monthlydates';

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
        $followUps = FollowUp::where('update_frequency','monthly')->get();
        foreach ($followUps as $followUp) {
            $followUp->update(['next_update_date' => now()->addMonth()]);
        }
        $followUp->save();
    }
    }

