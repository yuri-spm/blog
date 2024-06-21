<?php

namespace system\Model;


use system\Core\Model;
class PostModel extends Model
{

  public function __construct()
  {
      parent:: __construct('posts');
  }

  public function category(): ? CategoryModel
  {
    if($this->category_id){
      return (new CategoryModel())->findByID($this->category_id);
    }
    return null;
  }

  public function user(): ? UserModel
  {
    if($this->user_id){
      return (new UserModel())->findByID($this->user_id);
    }
    return null;
  }

  public function save(): bool
  {
      $this->slug();
      return parent::save();
  }
  
}
