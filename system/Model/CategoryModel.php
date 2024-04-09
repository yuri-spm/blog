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

   public function find(): array
   {
      $query = "SELECT * FROM category WHERE status = 1";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetchAll();

      return $result;
   }
   
   /**
    * findByID
    *
    * @param  mixed $id
    * @param  mixed $columns
    * @return bool
    */
   public function findByID(int $id): bool|object
   {
      $query = "SELECT * FROM category WHERE id = {$id}";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetch();
      return $result;
   }

   public function all(): array
   {
      $query = "SELECT * FROM category";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetchAll();

      return $result;
   }
   
   /**
    * posts
    *
    * @param  mixed $id
    * @param  mixed $columns
    * @return void
    */
   public function posts(int $id)
   {
      $query = "SELECT * FROM posts WHERE category_id = {$id} AND status = 1";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetchAll();
      return $result;
   }
   
   /**
    * register
    *
    * @param  mixed $data
    * @return void
    */
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
       try {
          $query = "UPDATE category SET  title = ?, text = ?, status = ? WHERE id = {$id}";
          
          $stmt = Connect::getInstance()->prepare($query);
          $stmt->execute(
            [
               $data['title'],
               $data['text'],
               $data['status']
            ]
          );
       } catch (PDOException $e) {
          echo (new Message())->error($e);
       }
   }

   public function delete(int $id)
   {
      $query = "DELETE FROM category WHERE `category`.`id` = {$id}";
      $stmt = Connect::getInstance()->query($query);
      $stmt->execute();
   }

   public function count(?string $term = null):int
   {
      $term = ($term ? "WHERE {$term}" : '');
      
      $query = "SELECT * FROM category {$term} ";
      $stmt = Connect::getInstance()->query($query);
      $stmt->execute();

      return $stmt->rowCount();
   }
}
