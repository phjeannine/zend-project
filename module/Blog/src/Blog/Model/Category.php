<?php
namespace Blog\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;

class Category implements InputFilterAwareInterface
{
    public $idCategory;
    public $name;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->idCategory = (isset($data['id_category'])) ? $data['id_category'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }
    
    // La méthode setInputFilter ne sera pas utilisé ici...
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }
    
    // La méthode qui nous intéresse
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
    
            $inputFilter->add(
                array(
                    'name'     => 'name',               // Le nom du champ / de la propriété
                    'required' => true,                 // Champ requis
                    'filters'  => array(                // Différents filtres:
                        array('name' => 'StripTags'),   // Pour retirer les tags HTML
                        array('name' => 'StringTrim'),  // Pour supprimer les espaces avant et apres le nom
                    ),
                    'validators' => array(              // Des validateurs
                        array(
                            'name'    => 'StringLength',// Pour vérifier la longueur du nom
                            'options' => array(
                                'encoding' => 'UTF-8',  // La chaine devra être en UTF-8
                                'min'      => 1,        // et une longueur entre 1 et 100
                                'max'      => 100,
                            ),
                        ),
                    ),
                )
                );
    
            $this->inputFilter = $inputFilter;
        }
    
        return $this->inputFilter;
    }
}