<?php
namespace Blog;

use Blog\Model\CategoryTable;
use Blog\Model\PostTable;

use Blog\Controller\CategoryController;
use Blog\Controller\IndexController;
use Blog\Controller\PostController;
use Zend\Mvc\Controller\ControllerManager;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /*
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Blog\Model\CategoryTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new CategoryTable($dbAdapter);
                    return $table;
                },
                'Blog\Model\PostTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new PostTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
    */
    
    public function getControllerConfig() {
        return array(
            'factories' => array(
                'Blog\Controller\Category' => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Front\Model\CategoryTable');
                    $post = $sm->get('Front\Model\JobTable');
                    $controller = new CategoryController($category, $post);
                    return $controller;
                },
                'Blog\Controller\Post'    => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Front\Model\CategoryTable');
                    $post = $sm->get('Front\Model\JobTable');
                    $controller = new PostController($category, $post);
                    return $controller;
                },
                'Blog\Controller\Index' => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Front\Model\CategoryTable');
                    $post = $sm->get('Front\Model\JobTable');
                    $controller = new IndexController($category, $post);
                    return $controller;
                },
            ),
        );
    }
}