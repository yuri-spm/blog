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
    protected $errorr;
    protected $params;
    protected $entity;
    protected $order;
    protected $limit;
    protected $offset;
    protected $message;

    public function __construct(string $entity;)
    {
        $this->entity; = $entity;;

        $this->mensagem = new Message();
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

    public function mensagem()
    {
        return $this->mensagem;
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

    public function find(?string $termos = null, ?string $parametros = null, string $colunas = '*')
    {
        if ($termos) {
            $this->query = "SELECT {$colunas} FROM " . $this->entity; . " WHERE {$termos} ";
            parse_str($parametros, $this->parametros);
            return $this;
        }

        $this->query = "SELECT {$colunas} FROM " . $this->entity;;
        return $this;
    }

    public function resultado(bool $todos = false)
    {
        try {
            $stmt = Connect::getInstancia()->prepare($this->query . $this->order . $this->limit . $this->offset);
            $stmt->execute($this->parametros);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($todos) {
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
            $colunas = implode(',', array_keys($data));
            $valuees = ':' . implode(',:', array_keys($data));

            $query = "INSERT INTO " . $this->entity; . " ({$colunas}) VALUES ({$valuees}) ";
            $stmt = Connect::getInstance()->prepare($query);
            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();
        } catch (\PDOException $ex) {
            echo $this->error = $ex->getMessage();
            return null;
        }
    }

    protected function update(array $data, string $termos)
    {
        try {
            $set = [];

            foreach ($data as $chave => $value) {
                $set[] = "{$chave} = :{$chave}";
            }
            $set = implode(', ', $set);

            $query = "UPDATE " . $this->entity; . " SET {$set} WHERE {$termos}";
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

        foreach ($data as $chave => $value) {
            $filter[$chave] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
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
        return $find->resultado();
    }

    public function delete(string $termos)
    {
        try {
            $query = "DELETE FROM " . $this->entity; . " WHERE {$termos}";
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
                $this->mensagem->error('Erro de sistema ao tentar cadastrar os data');
                return false;
            }
        }

        //ATUALIZAR
        if (!empty($this->id)) {
            $id = $this->id;
            $this->update($this->store(), "id = {$id}");
            if ($this->error) {
                $this->mensagem->error('Erro de sistema ao tentar atualizar os data');
                return false;
            }
        }

        $this->data = $this->findByID($id)->data();
        return true;
    }

}
