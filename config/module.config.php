<?php
return [
    'controllers' => [
        'abstract_factories' => [
            'Ajasta\Core\Mvc\Controller\AbstractConsoleControllerBridgeFactory',
            'Ajasta\Core\Mvc\Controller\AbstractHttpControllerBridgeFactory',
        ],
    ],
    'console_controllers' => [],
    'http_controllers' => [],
    'service_manager' => [
        'factories' => [
            'BaconController\Mvc\Controller\ConsoleControllerManager'
                => 'BaconController\Mvc\Controller\ConsoleControllerManagerFactory',
            'BaconController\Mvc\Controller\HttpControllerManager'
                => 'BaconController\Mvc\Controller\HttpControllerManagerFactory',
        ],
    ],
];
