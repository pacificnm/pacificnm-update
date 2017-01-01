<?php
return array(
    'module' => array(
        'Update' => array(
            'name' => 'Update',
            'version' => '1.0.5',
            'install' => array()
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Pacificnm\Update\Controller\Console' => 'Pacificnm\Update\Controller\Factory\ConsoleControllerFactory',
            'Pacificnm\Update\Controller\IndexController' => 'Pacificnm\Update\Controller\Factory\IndexControllerFactory',
            'Pacificnm\Update\Controller\CheckController' => 'Pacificnm\Update\Controller\Factory\CheckControllerFactory',
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Pacificnm\Update\Mapper\MysqlMapperInterface' => 'Pacificnm\Update\Mapper\Factory\MysqlMapperFactory',
            'Pacificnm\Update\Service\ServiceInterface' => 'Pacificnm\Update\Service\Factory\ServiceFactory'
        )
    ),
    'router' => array(
        'routes' => array(
            'update-index' => array(
                'pageTitle' => 'Update',
                'pageSubTitle' => 'Home',
                'activeMenuItem' => 'admin-index',
                'activeSubMenuItem' => 'update-index',
                'icon' => 'fa fa-download',
                'layout' => 'admin',
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/update',
                    'defaults' => array(
                        'controller' => 'Pacificnm\Update\Controller\IndexController',
                        'action' => 'index'
                    )
                )
            ),
            'update-check' => array(
                'pageTitle' => 'Update',
                'pageSubTitle' => 'Check',
                'activeMenuItem' => 'admin-index',
                'activeSubMenuItem' => 'update-index',
                'icon' => 'fa fa-download',
                'layout' => 'admin',
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/update/check',
                    'defaults' => array(
                        'controller' => 'Pacificnm\Update\Controller\CheckController',
                        'action' => 'index'
                    )
                )
            ),
            'update-install' => array(
                'pageTitle' => 'Update',
                'pageSubTitle' => 'Install',
                'activeMenuItem' => 'admin-index',
                'activeSubMenuItem' => 'update-index',
                'icon' => 'fa fa-download',
                'layout' => 'admin',
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/update/install',
                    'defaults' => array(
                        'controller' => 'Pacificnm\Update\Controller\IndexController',
                        'action' => 'index'
                    )
                )
            )
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'update-install' => array(
                    'options' => array(
                        'route' => 'update --install',
                        'defaults' => array(
                            'controller' => 'Pacificnm\Update\Controller\Console',
                            'action' => 'index'
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'controller_map' => array(
            'Pacificnm\Update' => true
        ),
        'template_map' => array(
            'pacificnm/update/check/index' => __DIR__ . '/../view/update/check/index.phtml',
            'pacificnm/update/create/index' => __DIR__ . '/../view/update/create/index.phtml',
            'pacificnm/update/delete/index' => __DIR__ . '/../view/update/delete/index.phtml',
            'pacificnm/update/index/index' => __DIR__ . '/../view/update/index/index.phtml',
            'pacificnm/update/update/index' => __DIR__ . '/../view/update/update/index.phtml',
            'pacificnm/update/view/index' => __DIR__ . '/../view/update/view/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'acl' => array(
        'default' => array(
            'guest' => array(),
            'user' => array(),
            'administrator' => array(
                'update-index',
                'update-install',
                'update-check'
            )
        )
    ),
    'menu' => array(
        'default' => array(
            array(
                'name' => 'Admin',
                'route' => 'admin-index',
                'icon' => 'fa fa-gear',
                'order' => 99,
                'location' => 'left',
                'active' => true,
                'items' => array(
                    array(
                        'name' => 'Update',
                        'route' => 'update-index',
                        'icon' => 'fa fa-download',
                        'order' => 10,
                        'active' => true,
                    )
                )
            )
        )
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Admin',
                'route' => 'admin-index',
                'useRouteMatch' => true,
                'pages' => array(
                    array(
                        'label' => 'Update',
                        'route' => 'update-index',
                        'useRouteMatch' => true,
                        'pages' => array(
                            array(
                                'label' => 'Check',
                                'route' => 'update-check',
                                'useRouteMatch' => true,
                            )
                        )
                    ),
                    
                )
            )
        )
    )
);