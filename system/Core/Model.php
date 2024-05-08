<?php

namespace system\Core;

use PDOException;
use system\Core\Connect;

class Model
{
    protected $data;
    protected $query;
    protected $erro;
    protected $params;
    protected $table;
    protected $order;
    protected $limit;
    protected $offset;


    public function __construct(string $table)
    {
        $this->table = $table;       
    }

    public function order(string $order)
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    public function limit(string $limit)
    {
        $this->limit = " LIMIT  {$limit}";
        return $this;
    }

    public function offset(string $offset)
    {
        $this->offset = " OFFSET  {$offset}";
        return $this;
    }

    public function find(?string $term = null, ?string $params = null, string $columns = '*')
    {
        if($term){
            $this->query = "SELECT {$columns} FROM ".$this->table." WHERE {$term}";
            parse_str($params, $this->params);
            return $this;
        }

        $this->query= "SELECT {$columns} FROM ".$this->table;
        return $this;
    }

    public function result(bool $all = false){
        try{
            $stmt = Connect::getInstance()->prepare($this->query.$this->order.$this->limit.$this->offset);
            $stmt->execute($this->params);

            if(!$stmt->rowCount()){
                return null;
            }

            if($all){
                return $stmt->fetchAll();
            }

            return $stmt->fetchObject();

        }catch(PDOException $e){
            $this->erro = $e;
        }
    }
    
        
   
}