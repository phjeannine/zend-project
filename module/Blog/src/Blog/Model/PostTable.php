<?php
namespace Blog\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class PostTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function fetchByIdCategoryWithLimit($idCategory, $limit)
    {
        $select = new Select();
        $select->from('post')
               ->where('id_category = ' . (int)$idCategory)
               ->limit((int)$limit);

        $resultSet = $this->tableGateway->select($select);
        return $resultSet;
    }
    
    public function fetchAllByIdCategory($idCategory)
    {
    	$id  = (int)$idCategory;
        $resultSet = $this->tableGateway->select(array('id_category' => $id));
        return $resultSet;
    }
    

    public function getPost($id)
    {
        $id  = (int)$id;
        $rowset = $this->tableGateway->select(array('id_post' => $id));
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
            'description' => $post->ingredients,
            'date' => $post->date,
            'id_category' => $post->idCategory
        );

        $id = (int)$post->idPost;

        if ($id == 0) {
            $this->tableGateway->insert($data);
        } elseif ($this->getPost($id)) {
            $this->tableGateway->update(
                $data,
                array(
                    'id_post' => $id,
                )
            );
        } else {
            throw new \Exception('Form id does not exist');
        }
    }

    public function deletePost($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}