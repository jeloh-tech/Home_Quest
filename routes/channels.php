<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{userId1}.{userId2}', function ($user, $userId1, $userId2) {
    // Allow access if the authenticated user is one of the participants in the conversation
    return (int) $user->id === (int) $userId1 || (int) $user->id === (int) $userId2;
});
