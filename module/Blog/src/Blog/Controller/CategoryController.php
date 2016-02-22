<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;
use Zend\View\Model\ViewModel;
use Blog\Form\CategoryForm;
use Blog\Model\Category;

class CategoryController extends AbstractActionController
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

    public function listAction()
    {
        $id_category = $this->params()->fromRoute('id', null);

        if (!is_null($id_category)) {
            $category = $this->categoryTable->getCategory($id_category);
            $posts = $this->postTable->fetchAllByIdCategory($id_category);
    
            return new ViewModel(
                array(
                    'category' => $category,
                    'posts'     => $posts
                )
            );
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
    
    public function addAction()
    {
        $formCategory = new CategoryForm();

        // On récupère l'objet Request
        $request = $this->getRequest();
        
        // On vérifie si le formulaire a été posté
        if ($request->isPost()) {
            // On instancie notre modèle Category
            $category= new Category();

            // Et on passe l'InputFilter de Category au formulaire
            $formCategory->setInputFilter($category->getInputFilter());
            $formCategory->setData($request->getPost());
        
            // Si le formulaire est valide
            if ($formCategory->isValid()) {
                // On prend les données du formulaire qui sont converti pour correspondre à notre modèle Category
                $category->exchangeArray($formCategory->getData());

                // On enregistre ces données dans la table Category
                $this->categoryTable->saveCategory($category);

                // Puis on redirige sur la page d'accueil.
                return $this->redirect()->toRoute('home');
            }

            // Si le formulaire n'est pas valide, on reste sur la page et les erreurs apparaissent
        }
        
        $this->getServiceLocator()->get('Zend\Log')->info('Une catégorie a été créée !');
        
        return new ViewModel(
            array(
                'form' => $formCategory
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