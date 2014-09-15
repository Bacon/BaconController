<?php
return [
    'controllers' => [
        'abstract_factories' => [
            'BaconController\Mvc\Controller\AbstractConsoleControllerBridgeFactory',
            'BaconController\Mvc\Controller\AbstractHttpControllerBridgeFactory',
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
