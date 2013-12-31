<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Account\Controller\Account' => 'Account\Controller\AccountController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'account' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Account\Controller\Account',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'account/account/login' => __DIR__ . '/../view/account/account/login.phtml',
            'account/account/index' => __DIR__ . '/../view/account/account/index.phtml',
            'layout/header' => __DIR__ . '/../../../common/view/layout/header.phtml',            
            'layout/footer' => __DIR__ . '/../../../common/view/layout/footer.phtml',            
            'lib/error' => __DIR__ . '/../../../common/view/lib/error.phtml',            
        ),
    ),
);
