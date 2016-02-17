<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'category' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/category[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
            'add_category' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/add/category[/]',
                    'defaults' => array(
                        'module'     => 'Blog',
                        'controller' => 'Blog\Controller\Category',
                        'action'     => 'add',
                    ),
                ),
            ),
            'add_post' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/add/post[/]',
                    'defaults' => array(
                        'module'     => 'Blog',
                        'controller' => 'Blog\Controller\Post',
                        'action'     => 'add',
                    ),
                ),
            ),
            'post' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/post[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Post',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    // On indique ici où sont les vues du module
    'view_manager' => array(
        'template_path_stack' => array(
            'front' => __DIR__ . '/../view',
        ),
    ),
);