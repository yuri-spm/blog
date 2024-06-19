<?php

namespace system\Model;

use PDOException;
use system\Core\Connect;
use system\Core\Message;
use system\Core\Model;

class CategoryModel extends Model
{
    public function __construct()
    {
        parent::__construct('category');
    }

    public function posts(): ?array
    {
        $find = (new PostModel())->find("category_id = {$this->id}");
        return $find->result(true);
    }
}