<?php
namespace Blog\Model;

class Post
{
    public $idPost;
    public $title;
    public $image;
    public $ingredients;
    public $description;
    public $date;
    public $idCategory;

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
}