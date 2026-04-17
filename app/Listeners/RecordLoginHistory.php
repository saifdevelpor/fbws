<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;

class RecordLoginHistory
{
    public function handle(Login $event): void
    {
        $request = request();

        LoginHistory::create([
            'user_id' => $event->user->id,
            'ip_address' => method_exists($request, 'ip') ? $request->ip() : null,
            'user_agent' => method_exists($request, 'userAgent') ? $request->userAgent() : null,
            'logged_in_at' => now(config('app.timezone')),
        ]);
    }
}
