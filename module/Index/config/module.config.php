<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Index\Controller\Index' => 'Index\Controller\IndexController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'index' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/index[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Index\Controller\Index',
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
            'index/index/index' => __DIR__ . '/../view/index/index/index.phtml',
            'layout/header' => __DIR__ . '/../../../common/view/layout/header.phtml',            
            'layout/footer' => __DIR__ . '/../../../common/view/layout/footer.phtml',            
        ),
    ),
);
