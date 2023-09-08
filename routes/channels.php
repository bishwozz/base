<?php

use App\Models\User;
use App\Models\Notifications;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function (User $user, int $roles_id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('data.{roles_id}', function ($user) {
    return $user->id === Notifications::findOrNew($roles_id)->roles_id;
});

