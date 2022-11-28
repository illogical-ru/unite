<?php

return [

    'charset'  => 'UTF-8',

    'timezone' => 'UTC',

    'home'     => NULL, // .../unite/api/pub
    'host'     => NULL, // api.unite
    'pub'      => '/',
    'origin'   => NULL, // http://localhost:3000

    'safe'     => [
        'sign'  => '0123456789ABCDEF0123456789ABCDEF',
    ],

    'storage'  => [
        [
            'driver'   => 'PDO',
            'dsn'      => (
                  'mysql:'
                . 'unix_socket=.../mysqld.sock;'
                . 'dbname=unite;'
            ),
            'username' => 'root',
            'password' =>  NULL,
        ]
    ],
];

?>
