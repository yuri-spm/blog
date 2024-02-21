<?php

namespace system\Model;

use system\Core\Connect;

class PostModel
{    
       
    /**
     * read
     *
     * @return array
     */
    public function read(): array
    {
        $query = "SELECT * FROM posts";
        $smtp = Connect::getInstance()->query($query);
        $result = $smtp->fetchAll();

        return $result;
    }
}