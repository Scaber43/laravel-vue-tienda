<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-panel', function (User $user) {
    return $user->esAdmin();
});
