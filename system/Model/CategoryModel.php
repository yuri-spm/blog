<?php

namespace system\Model;

use PDOException;
use system\Core\Connect;
use system\Core\Message;

class CategoryModel
{

    protected $query;

    /** @var string */
    protected $params;

    /** @var string */
    protected $order;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    public function find($columns = '*'): array
    {   
       $query = "SELECT {$columns} FROM category WHERE status = 1";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }

    public function findByID(int $id, $columns = '*'): bool|object
    {   
       $query = "SELECT {$columns} FROM category WHERE id = {$id}";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetch();
       return $result;
    }

    public function posts(int $id, $columns = '*')
    {   
       $query = "SELECT {$columns} FROM posts WHERE category_id = {$id} AND status = 1";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();
       return $result;
    }

    public function register($data=[])
    {
      
      try{
         $stmt = Connect::getInstance()->prepare('INSERT INTO categories(title, text, status) values (?,?,?)');
         $stmt->bindValue(1, $data['titulo']);
         $stmt->bindValue(2, $data['text']);
         $stmt->bindValue(3, $data['status']);
         $stmt->execute();

      }catch(PDOException $e){
        (new Message())->error($e);
      }
    }


}
