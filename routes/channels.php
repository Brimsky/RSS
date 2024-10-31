<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('chat', function ($user) {
    return Auth::check();
});

