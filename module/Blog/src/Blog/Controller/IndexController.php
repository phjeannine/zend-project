<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\PostTable;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

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
    
    public function sendMail() {
        $message = new Message();
        $message->addTo('amliebarre@gmail.com')
            ->addFrom('scarpa.zend@gmail.com')
            ->setSubject('Test send mail using ZF2');
            
        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            'host'              => 'smtp.gmail.com',
            'connection_class'  => 'login',
            'connection_config' => array(
                'ssl'       => 'tls',
                'username' => 'scarpa.zend@gmail.com',
                'password' => 'scarpa1234'
            ),
            'port' => 587,
        ));
        $html = new MimePart('<b>heii, <i>sorry</i>, i\'m going late</b>');
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->addPart($html);
        $message->setBody($body);
        $transport->setOptions($options);
        $transport->send($message);
    }

    public function indexAction()
    {
        $categories = $this->getCategoryTable()->fetchAll();
        $results = array();

        foreach ($categories as $category) {
            $posts = $this->getPostTable()->fetchAllByIdCategory($category->idCategory);
            $results[$category->name] = $posts;
        }
        
        $this->getServiceLocator()->get('Zend\Log')->info('Un utilisateur a accedé à la page index');
        $this->sendMail();
        
        return new ViewModel(
            array(
                'categories' => $results,
            )
        );
    }
}