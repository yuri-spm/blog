<?php

namespace system\Model;


use system\Core\Model;

class PostModel extends Model
{

    public function __construct()
    {
        parent::__construct('posts');
    }

     /**
     * Busca a categoria pelo ID
     * @return CategoryModel|null
     */
    public function categories(): ?CategoryModel
    {
        if ($this->category_id) {
            return (new CategoryModel())->findByID($this->category_id);
        }
        return null;
    }
    /**
     * Busca o usuário pelo ID
     * @return UserModel|null
     */
    public function user(): ?UserModel
    {
        if ($this->user_id) {
            return (new UserModel())->findByID($this->user_id);
        }
        return null;
    }

    /**
     * Salva o post com slug
     * @return bool
     */
    public function save(): bool
    {
        $this->slug();
        return parent::save();
    }
}
