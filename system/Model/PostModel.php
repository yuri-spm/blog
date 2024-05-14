<?php

namespace system\Model;

use PDOException;
use system\Controller\Model as ControllerModel;
use system\Core\Connect;
use system\Core\Message;
use system\Core\Model;
class PostModel extends Model
{

  const TABLE = 'posts';

  public function __construct()
  {
      parent:: __construct('posts');
  }
   
   // public function findByID(int $id): bool|object
   // {
   //    $query = "SELECT * FROM ".self::TABLE." WHERE id = {$id}";
   //    $stmt = Connect::getInstance()->query($query);
   //    $result = $stmt->fetch();
   //    return $result;
   // }

   public function all(): array
   {
      $query = "SELECT * FROM posts";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetchAll();

      return $result;
   }

   public function search($search): array
   {
      $query = "SELECT* FROM ".self::TABLE." WHERE status = 1 AND title LIKE '%{$search}%'";
      $stmt = Connect::getInstance()->query($query);
      $result = $stmt->fetchAll();

      return $result;
   }

   // public function register(array $data)
   // {
   //    try {

   //       $query = 'INSERT INTO posts(`category_id`,`title`, `text`, `status`) VALUES (?,?,?,?)';
   //       $stmt = Connect::getInstance()->prepare($query);
   //       $stmt->execute(
   //          [
   //             $data['category_id'],
   //             $data['title'],
   //             $data['text'],
   //             $data['status']
   //          ]
   //       );
   //    } catch (PDOException $e) {
   //       echo (new Message())->error($e);
   //    }
   // }

   // public function update(array $data, int $id): void
   // {
   //    try {
   //       $query = "UPDATE posts SET category_id = ?, title = ?, text = ?, status = ? WHERE id = {$id}";
   //       $stmt = Connect::getInstance()->prepare($query);
   //       $stmt->execute([
   //          $data['category_id'],
   //          $data['title'],
   //          $data['text'],
   //          $data['status']
   //       ]);
   //    } catch (PDOException $e) {
   //       echo (new Message())->error($e);
   //    }
   // }

   public function delete(int $id)
   {
      $query = "DELETE FROM ".self::TABLE." WHERE `posts`.`id` = {$id}";
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
