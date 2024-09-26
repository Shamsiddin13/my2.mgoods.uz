<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\Target;
use Illuminate\Console\Command;

class SendTargetNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-target-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a target notification to a user';

    public function handle()
    {
        $user = User::find(19); // The user you want to notify
        $user->notify(new Target());

        $this->info('Notification sent successfully.');
    }
}
