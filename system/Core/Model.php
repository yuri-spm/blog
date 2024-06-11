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
    protected $entity;
    protected $order;
    protected $limit;
    protected $offset;
    protected $message;

    public function __construct(string $entity)
    {
        $this->entity = $entity;

        $this->message = new Message();
    }

    public function order(string $order)
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    public function limit(string $limit)
    {
        $this->limite = " LIMIT {$limit}";
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
        if (empty($this->data)) {
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
            $this->query = "SELECT {$columns} FROM " . $this->entity . " WHERE {$terms} ";
            if($params !== null){
                parse_str($params, $this->params);
            }
            return $this;
        }

        $this->query = "SELECT {$columns} FROM " . $this->entity;
        return $this;
    }

    public function result(bool $all = false)
    {
        try {
            $stmt = Connect::getInstance()->prepare($this->query . $this->order . $this->limite . $this->offset);
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

    protected function register(array $data)
    {
        try {
            $columns = implode(',', array_keys($data));
            $valuees = ':' . implode(',:', array_keys($data));

            $query = "INSERT INTO " . $this->entity . " ({$columns}) VALUES ({$valuees}) ";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();
        } catch (\PDOException $ex) {
            echo $this->error = $ex->getMessage();
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

            $query = "UPDATE " . $this->entity . " SET {$set} WHERE {$terms}";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filter($data));

            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $ex) {
            echo $this->error = $ex->getMessage();
            return null;
        }
    }

    private function filter(array $data)
    {
        $filter = [];

        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }

        return $filter;
    }

    protected function store()
    {
        $data = (array) $this->data;

        return $data;
    }

    public function findByID(int $id)
    {
        $find = $this->find("id = {$id}");
        return $find->result();
    }

    public function delete(string $terms)
    {
        try {
            $query = "DELETE FROM " . $this->entity . " WHERE {$terms}";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute();

            return true;
        } catch (\PDOException $ex) {
            $this->error = $ex->getMessage();
            return null;
        }
    }
    
    public function destroy()
    {
        if(empty($this->id)){
            return false;
        }
        
        $destroy = $this->delete("id = {$this->id}");
        return $destroy;
    }

    public function count(): int
    {
        $stmt = Connect::getInstance()->prepare($this->query);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function save(): bool
    {
        //CADASTRAR
        if (empty($this->id)) {
            $id = $this->register($this->store());
            if ($this->error) {
                $this->message->error('Erro de sistema ao tentar registrar os dados');
                return false;
            }
        }

        //ATUALIZAR
        if (!empty($this->id)) {
            $id = $this->id;
            $this->update($this->store(), "id = {$id}");
            if ($this->error) {
                $this->message->error('Erro de sistema ao tentar atualizar os dados');
                return false;
            }
        }

        $this->data = $this->findByID($id)->data();
        return true;
    }

}
