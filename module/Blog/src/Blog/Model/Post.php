<?php
namespace Blog\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;

class Post implements InputFilterAwareInterface
{
    public $idPost;
    public $title;
    public $image;
    public $ingredients;
    public $description;
    public $date;
    public $idCategory;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->idPost = (isset($data['id_post'])) ? $data['id_post'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->image = (isset($data['image'])) ? $data['image'] : null;
        $this->ingredients = (isset($data['ingredients'])) ? $data['ingredients'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->idCategory = (isset($data['id_category'])) ? $data['id_category'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
    
            $inputFilter->add(
                array(
                    'name'     => 'id_category',
                    'required' => true,
                )
            );
    
            $inputFilter->add(
                array(
                    'name' => 'title',
                    'required' => true,
                )
            );
    
            $inputFilter->add(
                array(
                    'name' => 'image',
                    'required' => true,
                )
            );
            
            $inputFilter->add(
                array(
                    'name' => 'ingredients',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 600,
                            )
                        )
                    )
                )
            );
    
            $inputFilter->add(
                array(
                    'name' => 'description',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 600,
                            )
                        )
                    )
                )
            );
            
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}