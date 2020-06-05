<?php

/**
 * El usuario autenticado
 * El Id que hemos pasado
 */
Broadcast::channel('mensaje.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('escribiendo', function ($user) {
    return Auth::check();
});
// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
