<?php
return array(
    'module' => array(
        'Update' => array(
            'name' => 'Update',
            'version' => '1.0.3',
            'install' => array()
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Update\Controller\Console' => 'Update\Controller\Factory\ConsoleControllerFactory',
            'Update\Controller\IndexController' => 'Update\Controller\Factory\IndexControllerFactory',
            'Update\Controller\CheckController' => 'Update\Controller\Factory\CheckControllerFactory',
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Update\Mapper\MysqlMapperInterface' => 'Update\Mapper\Factory\MysqlMapperFactory',
            'Update\Service\ServiceInterface' => 'Update\Service\Factory\ServiceFactory'
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
                        'controller' => 'Update\Controller\IndexController',
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
                        'controller' => 'Update\Controller\CheckController',
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
                        'controller' => 'Update\Controller\IndexController',
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
                            'controller' => 'Update\Controller\Console',
                            'action' => 'index'
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
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