<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\PostTable;

class IndexController extends AbstractActionController
{
    protected $postTable;
    protected $categoryTable;

    public function getPostTable()
    {
        if (!$this->postTable) {
            $sm = $this->getServiceLocator();
            $this->postTable = $sm->get('Blog\Model\PostTable');
        }
        return $this->postTable;
    }

    public function getCategoryTable()
    {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Blog\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

    // Action index qui sera appelé par défaut
    public function indexAction()
    {
        $categories = $this->getCategoryTable()->fetchAll();
        $results = array();

        foreach ($categories as $category) {
            $posts = $this->getPostTable()->fetchAllByIdCategory($category->idCategory);
            $results[$category->name] = $posts;
        }

        return new ViewModel(
            array(
                'categories' => $results,
            )
        );
    }
}