<?php

namespace system\Core;

use PDOException;
use stdClass;
use system\Core\Connect;
use system\Core\Message;

abstract class Model
{
    protected $data;
    protected $query;
    protected $error;
    protected $params;
    protected $table;
    protected $order;
    protected $limit;
    protected $offset;
    protected $message;


    public function __construct(string $table)
    {
        $this->table = $table;
        
        $this->message = new Message();
    }

    public function order(string $order)
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    public function limit(string $limit)
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function offset(string $offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }
    
    public function error()
    {
        return $this->error;
    }
    
    public function message()
    {
        return $this->message;
    }
    
    public function data()
    {
        return $this->data;
    }
    
    public function __set($name, $value)
    {
        if(empty($this->data)){
            $this->data = new \stdClass();
        }
        
        $this->data->$name = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->data->$name);
    }
    
    public function __get($name)
    {
        return $this->data->$name ?? null;
    }

    public function find(?string $terms = null, ?string $params = null, string $columns = '*')
    {
        if ($terms) {
            $this->query = "SELECT {$columns} FROM " . $this->table . " WHERE {$terms} ";
            if($params !== null){
                parse_str($params, $this->params);
            }
            
            return $this;
        }

        $this->query = "SELECT {$columns} FROM " . $this->table;
        return $this;
    }

    public function result(bool $all = false)
    {
        try {
            $stmt = Connect::getInstance()->prepare($this->query . $this->order . $this->limit . $this->offset);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                return $stmt->fetchAll();
            }

            return $stmt->fetchObject(static::class);
        } catch (\PDOException $ex) {
            echo $this->error = $ex;
            return null;
        }
    }

    protected function create(array $data)
    {
        try {
            $columns = implode(',', array_keys($data));
            $value = ':' . implode(',:', array_keys($data));

            $query = "INSERT INTO " . $this->table . " ({$columns}) VALUES ({$value}) ";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filtro($data));

            return Connect::getInstance()->lastInsertId();
        } catch (\PDOException $ex) {
            echo $this->error = $ex;
            return null;
        }
    }

    protected function update(array $data, string $terms)
    {
        try {
            $set = [];

            foreach ($data as $key => $value) {
                $set[] = "{$key} = :{$key}";
            }
            $set = implode(', ', $set);

            $query = "UPDATE ".$this->table." SET {$set} WHERE {$terms}";            
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filtro($data));
            
            return ($stmt->rowCount() ?? 1);
            
        } catch (\PDOException $ex) {
            echo $this->error = $ex;
            return null;
        }
    }

    private function filtro(array $data)
    {
        $filtro = [];

        foreach ($data as $key => $valor) {
            $filtro[$key] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }
        
        return $filtro;
    }
    
    
    protected function store()
    {
        $data = (array) $this->data;
        
        return $data;
    }
    
    public function findByID(int $id)
    {
        $busca = $this->find("id = {$id}");
        return $busca->result();
    }
    
    public function save()
    {
        //CADASTRAR
        if(empty($this->id)){
            $id = $this->create($this->store());
            if($this->error){
                $this->mensagem->error('error de sistema ao tentar cadastrar os data');
                return false;
            }
        }
        
        //ATUALIZAR
        if(!empty($this->id)){
            $id = $this->id;
            $this->update($this->store(), "id = {$id}");
            if($this->error){
                $this->mensagem->error('error de sistema ao tentar atualizar os data');
                return false;
            }
        }
        
        $this->data = $this->findByID($id)->data();
        return true;
    }

    public function delete(string $terms)
    {
        try {
            $query = "DELETE FROM ".$this->table." WHERE {$terms}";            
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute();
            
            return true;
            
        } catch (\PDOException $ex) {
            echo $this->error = $ex;
            return null;
        }
    }
    public function destroy()
    {
        if(empty($this->id)){
            return false;
        }
        $delete = $this->delete("id = {$this->id}");
        return $delete;
    }

    public function count():int
    {
       $stmt = Connect::getInstance()->prepare($this->query);
       $stmt->execute();
 
       return $stmt->rowCount();
    }
 

}
