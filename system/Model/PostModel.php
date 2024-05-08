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

   public function find(?string $term = null): array
   {
      $term = ($term ? "WHERE {$term}" : '');
      $query = "SELECT * FROM posts {$term} ";
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

   public function register(array $data)
   {
      try {

         $query = 'INSERT INTO posts(`category_id`,`title`, `text`, `status`) VALUES (?,?,?,?)';
         $stmt = Connect::getInstance()->prepare($query);
         $stmt->execute(
            [
               $data['category_id'],
               $data['title'],
               $data['text'],
               $data['status']
            ]
         );
      } catch (PDOException $e) {
         echo (new Message())->error($e);
      }
   }

   public function update(array $data, int $id): void
   {
      try {
         $query = "UPDATE posts SET category_id = ?, title = ?, text = ?, status = ? WHERE id = {$id}";
         $stmt = Connect::getInstance()->prepare($query);
         $stmt->execute([
            $data['category_id'],
            $data['title'],
            $data['text'],
            $data['status']
         ]);
      } catch (PDOException $e) {
         echo (new Message())->error($e);
      }
   }

   public function delete(int $id)
   {
      $query = "DELETE FROM posts WHERE `posts`.`id` = {$id}";
      $stmt = Connect::getInstance()->query($query);
      $stmt->execute();
   }

   public function count(?string $term = null):int
   {
      $term = ($term ? "WHERE {$term}" : '');
      
      $query = "SELECT * FROM posts {$term} ";
      $stmt = Connect::getInstance()->query($query);
      $stmt->execute();

      return $stmt->rowCount();
   }
}
