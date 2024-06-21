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

    public function posts($id): ?array
    {
        $find = (new PostModel())->find("category_id = {$id}");
        return $find->result(true);
    }

    public function user():?UserModel
    {
        if($this->users_id){
            return (new UserModel())->findByID($this->user_id);
        }
    }
}