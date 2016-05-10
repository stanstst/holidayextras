<?php

return [
    'delivery'       => [
        'request'  => '\Application\Delivery\Web\Request',
        'response' => '\Application\Delivery\Web\Response',
    ],
    'persistence'    => [
        'repository'    => '\Application\Persistence\Mysql\DoctrineRepository',
        'entityPath'    => __DIR__ . '/Application/Entity',
        'entityYmlPath' => __DIR__ . '/Application/Entity/DoctrineOrm',
    ],
    'restActions'    => [
        'unIdentified' => ['get' => 'getList', 'put' => null, 'post' => 'create', 'delete' => 'deleteAll'],
        'identified'   => ['get' => 'get', 'put' => 'update', 'post' => null, 'delete' => 'delete'],
    ],
    'doctrineParams' => [
        'driver' => 'pdo_sqlite',
        'path'   => __DIR__ . '/holiday.sqlite',
        //        'user'     => 'holiday',
        //        'password' => 'holiday',
        //        'dbname'   => 'holiday',
    ],
];