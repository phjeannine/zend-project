<?php

namespace Blog\Form;

use Zend\Form\Form;
use Blog\Model\CategoryTable;

class PostForm extends Form
{
    protected $categoryTable = null;

    public function __construct(CategoryTable $table)
    {
        parent::__construct('Post');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->categoryTable = $table;

        $this->add(
            array(
                'name' => 'id_post',
                'type' => 'Hidden',
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )
        );

        $this->add(
            array(
                'name' => 'id_category',
                'type' => 'Select',
                'attributes' => array(
                    'id'    => 'id_category'
                ),
                'options' => array(
                    'label' => 'Catégory',
                    'value_options' => $this->getCategoryOptions(),
                    'empty_option'  => '--- Sélectionnez une categorie---'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'title',
                'type' => 'Text',
                'attributes' => array(
                    'id'    => 'title'
                ),
                'options' => array(
                    'label' => 'Titre'
                )
            )
        );

        $this->add(
            array(
                'name' => 'image',
                'type' => 'File',
                'attributes' => array(
                    'id'    => 'image'
                ),
                'options' => array(
                    'label' => 'Image'
                )
            )
        );

        $this->add(
            array(
                'name' => 'ingredients',
                'type' => 'Textarea',
                'attributes' => array(
                    'id'    => 'ingredients',
                    'rows' => 4,
                    'cols' => 30,
                ),
                'options' => array(
                    'label' => 'Ingrédients'
                )
            )
        );

        $this->add(
            array(
                'name' => 'description',
                'type' => 'Textarea',
                'attributes' => array(
                    'id'    => 'description',
                    'rows' => 4,
                    'cols' => 30,
                ),
                'options' => array(
                    'label' => 'Description de la recette'
                )
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Enregistrer',
                    'id' => 'submit',
                ),
            )
        );
    }

    protected function getCategoryOptions()
    {
        $data = $this->categoryTable->fetchAll();
        $selectData = array();
        
        foreach ($data as $selectOption) {
        $selectData[$selectOption->idCategory] = $selectOption->name;
        }
        
        return $selectData;
    }
}