<?php

set_include_path('../..');

require_once 'app.php';

// --- header --------------------------------------------------------------- //

if (App::conf()->origin) {
    header(
          'Access-Control-Allow-Origin: '
        . App::conf()->origin
    );
}

// --- routes --------------------------------------------------------------- //

$routes = [

    '*path'   => [
        'OPTIONS' => function() {

            $methods = App::routes_allow_methods();

            if ($methods) {
                header(
                      'Access-Control-Allow-Methods: '
                    . join(', ', $methods)
                );
                header(
                      'Access-Control-Allow-Headers: '
                    . 'Content-Type, Authorization'
                );
            }
        },
    ],

    'env'     => [
        'GET'     => function() {

            $user = App::user();
            $env  = [];

            if   ($user) {

                $avatar = $user->avatar();

                $env['email']   = $user->email;
                $env['avatar']  = $avatar ? $avatar->url() : NULL;
            }
            else {
                $env['isGuest'] = TRUE;
            }

            App::render(['json' => $env]);
        },
    ],

    'signup'  => [
        'POST'    => 'User::signup',
    ],
    'signin'  => [
        'POST'    => 'User::signin',
    ],
    'signout' => [
        'POST'    => 'User::signout',
    ],

    'avatar'  => [
        'POST'    => 'Avatar',
        'DELETE'  => 'Avatar',
    ],
];

foreach ($routes as $rule => $methods) {
    foreach ($methods as $method => $to) {

        $route = App::route("v1/${rule}", $method);

        if     (is_callable($to)) {
            $route->to($to);
        }
        elseif (is_string  ($to)) {
            list($controller, $method) = explode(
                '::', $to . '::' . strtolower($method)
            );
            $route->to(
                'Controller\\' . $controller, $method
            );
        }
    }
}

$result = App::routes_match();

if     ($result === FALSE) {
    header('HTTP/1.0 404 Not Found');
}
elseif ($result ==  401) {
    header('HTTP/1.0 401 Unauthorized');
    header('WWW-Authenticate: Bearer');
}
elseif ($result ==  403) {
    header('HTTP/1.0 403 Forbidden');
}

?>
