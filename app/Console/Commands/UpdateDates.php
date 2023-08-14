<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FollowUp;


class UpdateDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDates:dates';

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
        $followUps = FollowUp::whereIn('update_frequency','daily')->get();
        foreach ($followUps as $followUp) {
                    $followUp->update(['next_update_date' => now()]);
            }
            $followUp->save();
        }


    }

