<?php

namespace system\Model;

use PDOException;
use system\Core\Connect;
use system\Core\Message;
use system\Core\Model;

class CategoryModel extends Model
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('category');
    }
    
    /**
     * posts
     *
     * @param  mixed $id
     * @return array
     */
    public function posts($id): ?array
    {
        $find = (new PostModel())->find("category_id = {$id}");
        return $find->result(true);
    }
    
    /**
     * user
     *
     * @return UserModel
     */
    public function user():?UserModel
    {
        if($this->users_id){
            return (new UserModel())->findByID($this->user_id);
        }
    }
}