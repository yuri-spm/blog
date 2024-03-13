<?php

namespace system\Model;

use PDOException;
use system\Core\Connect;
use system\Core\Message;

class PostModel
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

    public function find(): array
    {   
       $query = "SELECT * FROM posts WHERE status = 1 ";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }

    public function findByID(int $id): bool|object
    {   
       $query = "SELECT * FROM posts WHERE id = {$id}";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetch();
       return $result;
    }

    public function all(): array
    {   
       $query = "SELECT * FROM posts";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }

    public function search($search): array
    {   
       $query = "SELECT* FROM posts WHERE status = 1 AND title LIKE '%{$search}%'";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }

    public function register($data = [])
    {
       try {
 
          $query = 'INSERT INTO posts(`category_id`,`title`, `text`, `status`) VALUES (?,?,?,?)';
          $stmt = Connect::getInstance()->prepare($query);
          $stmt->execute(
             [
                $data[ 'categoria_id'],
                $data[ 'titulo'],
                $data['text'],
                $data['status']
             ]);
       } catch (PDOException $e) {
          echo (new Message())->error($e);
       }
    }


}
