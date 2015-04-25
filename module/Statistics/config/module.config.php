<?php
return array(
    'router' => array(
        'routes' => array(
            'statistics-index' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/statistics',
                    'defaults' => array(
                        'controller'    => 'Statistics\Controller\Statistics',
                        'action'        => 'index',
                    ),
                ),
            ),
            'statistics-dailytotals' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/statistics/dailytotals',
                    'defaults' => array(
                        'controller'    => 'Statistics\Controller\Statistics',
                        'action'        => 'dailytotals',
                    ),
                ),
            ),
            'statistics-filetotals' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/statistics/filetotals[/:filename]',
                    'defaults' => array(
                        'controller'    => 'Statistics\Controller\Statistics',
                        'action'        => 'filetotals',
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Statistics',
                'route' => 'statistics-index',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Statistics\Controller\Statistics' => 'Statistics\Factory\LogFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Statistics/Model/LogTable' => 'Statistics\Model\LogTableFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Statistics' => __DIR__ . '/../view',
        ),
    ),
);
