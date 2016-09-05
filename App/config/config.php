<?php

return array(
    'mode'       => 'dev', // 'dev' or 'prod'
    'routes'     => include('routes.php'),
    'annotation' => true,
    'namespaces' => [
        'Yurii' => 'vendor/',
        'Test'  => 'src/',
        'Shop'  => 'src/',
        'App'   => ''
    ],
    'database'   => [
        'engine'   => 'mysql',
        'database' => 'mydb',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '5298r1',
        'port'     => '3306'
    ]
);