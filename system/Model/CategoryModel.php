<?php

namespace system\Model;

use PDOException;
use system\Core\Connect;
use system\Core\Message;

class CategoryModel
{
    public function find(?string $terms = null): array
    {
        $terms = ($terms ? "WHERE {$terms}" : '');
        
        $query = "SELECT * FROM category {$terms} "; 
        $stmt = Connect::getInstance()->query($query);
        $result = $stmt->fetchAll();

        return $result;        
    }
    
    public function findByID(int $id): bool|object
    {
        $query = "SELECT * FROM category WHERE id = {$id} "; 
        $stmt = Connect::getInstance()->query($query);
        $result = $stmt->fetch();

        return $result; 
    }
    
    public function posts(int $id): array
    {
        $query = "SELECT * FROM posts WHERE category_id = {$id} AND status = 1 ORDER BY id DESC "; 
        $stmt = Connect::getInstance()->query($query);
        $result = $stmt->fetchAll();

        return $result;        
    }
    
    public function register(array $data)
    {
       try {
          $query = 'INSERT INTO category(`title`, `text`, `status`) VALUES (?,?,?)';
          $stmt = Connect::getInstance()->prepare($query);
          $stmt->execute(
             [
                $data['title'],
                $data['text'],
                $data['status']
             ]);
       } catch (PDOException $e) {
          echo (new Message())->error($e);
       }
    }
    
    public function update(array $data, int $id):void
    {
        $query = "UPDATE category SET title = ?, text = ?, status = ? WHERE id = {$id} ";
        $stmt = Connect::getInstance()->prepare($query);
        $stmt->execute([$data['title'],$data['text'],$data['status']]);
    }
    
     public function delete(int $id):void
    {
        $query = "DELETE FROM category WHERE id = {$id} ";
        $stmt = Connect::getInstance()->prepare($query);
        $stmt->execute();
    }
    
    public function count(?string $terms = null): int
    {
        $terms = ($terms ? "WHERE {$terms}" : '');

        $query = "SELECT * FROM category {$terms}";
        $stmt = Connect::getInstance()->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }
    
}
