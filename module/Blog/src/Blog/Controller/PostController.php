<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;
use Zend\View\Model\ViewModel;
use Blog\Form\PostForm;
use Blog\Model\Post;
use Zend\Validator\File\Size;

class PostController extends AbstractActionController
{
    protected $postTable;
    protected $categoryTable;
    
    public function __construct($category, $post)
    {
        $this->categoryTable = $category;
        $this->postTable = $post;
    }
    
    public function setConfig($config)
    {
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
         
        if (!is_array($config)) {
            throw new RuntimeException(
                sprintf(
                    'Expected array or Traversable Jobeet configuration; received %s',
                    (is_object($config) ? get_class($config) : gettype($config))
                    )
                );
        }
        $this->config = $config;
    }
    
    public function setCategoryTable($category)
    {
    	$this->categoryTable = $category;
    }
    
    public function setPostTable($post)
    {
    	$this->postTable = $post;
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }

    public function getAction()
    {
        $id_post = $this->params()->fromRoute('id', null);
        
        if (!is_null($id_post)) {
            $post = $this->postTable->getPost($id_post);
            $category = $this->categoryTable->getCategory($post->idCategory);
        
            return new ViewModel(
                array(
                    'post'     => $post,
                    'category' => $category
                )
            );
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
    
    public function addAction()
    {
        $formPost = new PostForm($this->categoryTable);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $post = new Post();
            $formPost->setInputFilter($post->getInputFilter());
            
            $nonFiles = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();
            
            // Pour ZF 2.2.x uniquement
            $data = array_merge_recursive(
                $nonFiles,
                $files
            );
            
            $formPost->setData($data);
        
            if ($formPost->isValid()) {
                $size = new Size(array('max' => 716800));
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $files['image']);

                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key => $row) {
                        $error[] = $row;
                    }
                    $formPost->setMessages(array('image' => $error ));
                } else {
                    $adapter->setDestination('./public/img/');
                 
                    if ($adapter->receive($files['image']['name'])) {
                        $post->exchangeArray($formPost->getData());
                        $post->image = $files['image']['name'];

                        $this->postTable->savePost($post);
                        return $this->redirect()->toRoute('home');
                    }
                }
            }
        }
        
        return new ViewModel(
            array(
                'form' => $formPost
            )
        );
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}