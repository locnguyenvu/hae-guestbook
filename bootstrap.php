<?php
return [
    'services' => [
        'config' => [
            'class' => \Hae\Core\Config::class,
        ],
        'request' => [
            'class' => \Hae\Core\HttpRequest::class
        ],
        'response' => [
            'class' => \Hae\Core\HttpResponse::class
        ],
        'db' => [
            'class' => \Hae\Core\DatabaseConnection::class,
            'arguments' => [
                'sqlite:'.ROOT_PATH.'/db/schema.sqlite3',
            ]
        ]
    ]
];