<?php

namespace system\Model;

use system\Core\Connect;

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

    public function find($columns = '*'): array
    {   
       $query = "SELECT {$columns} FROM posts";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetchAll();

        return $result;
    }

    public function findByID(int $id, $columns = '*'): bool|object
    {   
       $query = "SELECT {$columns} FROM posts WHERE id = {$id}";
       $stmt = Connect::getInstance()->query($query);
       $result = $stmt->fetch();
       return $result;
    }


}
