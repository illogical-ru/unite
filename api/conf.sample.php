<?php

return [

    'charset'  => 'UTF-8',

    'timezone' => 'UTC',

    'safe'     => [
        'algo'  => 'md5',
        'sign'  => '0123456789ABCDEF0123456789ABCDEF',
    ],

    'origin'   => 'http://localhost:3000',

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
