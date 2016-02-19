<?php
namespace Blog;

use Blog\Controller\IndexController;

use Blog\Controller\CategoryController;
use Blog\Controller\PostController;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Blog\Model\Post;
use Blog\Model\Category;
use Blog\Model\CategoryTable;
use Blog\Model\PostTable;
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
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Blog\Model\CategoryTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Category());
                	return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },
                'Blog\Model\PostTable' =>  function($sm) {
                	$tableGateway = $sm->get('PostTableGateway');
                	$table = new PostTable($tableGateway);
                	return $table;
                },
                'PostTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Post());
                	return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
    public function getControllerConfig() {
        return array(
            'factories' => array(
                'Blog\Controller\Category' => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Blog\Model\CategoryTable');
                    $post = $sm->get('Blog\Model\PostTable');
                    $controller = new CategoryController($category, $post);
                    return $controller;
                },
                'Blog\Controller\Post' => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Blog\Model\CategoryTable');
                    $post = $sm->get('Blog\Model\PostTable');
                    $controller = new PostController($category, $post);
                    return $controller;
                },
                'Blog\Controller\Index' => function(ControllerManager $cm) {
                    $sm   = $cm->getServiceLocator();
                    $category = $sm->get('Blog\Model\CategoryTable');
                    $post = $sm->get('Blog\Model\PostTable');
                    $controller = new IndexController($category, $post);
                    return $controller;
                },
            ),
        );
    }
}