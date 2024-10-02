<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;

class SendWelcomeEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:welcome-email {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a welcome email to a specific user by user ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        // Dispatch the welcome email job
        SendWelcomeEmail::dispatch($user);

        $this->info('Welcome email sent successfully.');
        return 0;
    }
}
