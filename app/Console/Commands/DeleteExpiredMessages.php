<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:delete-expired';
    protected $description = 'Delete messages that have expired';

    public function handle()
    {
        $count = \App\Models\Message::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();

        $this->info("Deleted {$count} expired messages.");
    }
}
