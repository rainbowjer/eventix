<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class BackfillUserLoginTracking extends Command
{
    protected $signature = 'users:backfill-login-tracking';
    protected $description = 'Backfill last_login_at and login_count for users who have never logged in.';

    public function handle()
    {
        $updated = 0;
        User::whereNull('last_login_at')->orWhere('login_count', 0)->chunk(100, function ($users) use (&$updated) {
            foreach ($users as $user) {
                $fields = [];
                if (is_null($user->last_login_at)) {
                    $fields['last_login_at'] = $user->created_at;
                }
                if ($user->login_count == 0) {
                    $fields['login_count'] = 1;
                }
                if ($fields) {
                    $user->update($fields);
                    $updated++;
                }
            }
        });
        $this->info("Backfilled $updated users.");
        return 0;
    }
} 