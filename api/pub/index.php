<?php

set_include_path('..');

require_once 'app.php';


// --- header --------------------------------------------------------------- //

if (App::conf()->origin) {
    header('Access-Control-Allow-Origin: ' . App::conf()->origin);
}

// --- controllers ---------------------------------------------------------- //

App::route('*path', 'OPTIONS')
    ->to  (function() {

        $methods = App::routes_allow_methods();

        if ($methods) {
            header('Access-Control-Allow-Methods: ' . join(', ', $methods));
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
        }
    });


App ::route('env', 'GET')
    ->to   (function() {

        $user = App::user();
        $env  = [];

        if   ($user) {
            $env['email']   = $user->email;
        }
        else {
            $env['isGuest'] = TRUE;
        }

        App::render(['json' => $env]);
    });

App ::route('signup', 'POST')
    ->cond (['is_guest' => TRUE])
    ->to   ('Controller\User', 'signup');

App ::route('signin', 'POST')
    ->cond (['is_guest' => TRUE])
    ->to   ('Controller\User', 'signin');

App ::route('signout', 'POST')
    ->cond (['is_guest' => FALSE])
    ->to   ('Controller\User', 'signout');

// --- 404 ------------------------------------------------------------------ //

if (!App::routes_match()) {
    App::render([function() {
        header('HTTP/1.0 404 Not Found');
    }]);
}

?>
