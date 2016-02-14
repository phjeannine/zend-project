<?php
namespace Blog\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class PostTable extends AbstractTableGateway
{
    protected $table ='post';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Post());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getPost($id)
    {
        $id  = (int)$id;
        $rowset = $this->select(array('id_post' => $id));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row $id");
        }

        return $row;
    }

    public function savePost(Post $post)
    {
        $data = array(
            'id_post' => $post->idPost,
            'title' => $post->title,
            'image' => $post->image,
            'ingredients' => $post->ingredients,
            'description' => $post->description,
            'date' => $post->date,
            'id_category' => $post->idCategory
        );

        $id = (int)$post->idPost;

        if ($id == 0) {
            $this->insert($data);
        } elseif ($this->getPost($id)) {
            $this->update(
                $data,
                array(
                    'id_post' => $id,
                )
                );
        } else {
            throw new \Exception('Form id does not exist');
        }
    }
    
    public function fetchAllByIdCategory($id_category) {
        $resultSet = $this->select(array('id_category'=>$id_category));
        return $resultSet;
    }

    public function deletePost($id)
    {
        $this->delete(array('id_post' => $id));
    }
}