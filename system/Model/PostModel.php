<?php

namespace system\Model;

use system\Core\Connect;

class PostModel
{



    public function find($id = null, $columns = '*'): array
    {   
       $where = ($id ? "WHERE id = {$id}" : '');


       $query = "SELECT * FROM posts {$where}";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }
}
