<?php
return array(
    'router' => array(
        'routes' => array(
            'logs-index' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/logs',
                    'defaults' => array(
                        'controller'    => 'Logs\Controller\Logs',
                        'action'        => 'index',
                    ),
                ),
            ),
            'logs-process' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/logs/process[/:bucket]',
                    'defaults' => array(
                        'controller'    => 'Logs\Controller\Logs',
                        'action'        => 'process',
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Logs',
                'route' => 'logs-index',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Logs\Controller\Logs' => 'Logs\Factory\LogFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Logs/Model/LogTable' => 'Logs\Model\LogTableFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Logs' => __DIR__ . '/../view',
        ),
    ),
);
