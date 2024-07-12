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

    /**
     * __construct
     *
     * @param  mixed $entity
     * @return void
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;

        $this->message = new Message();
    }

    /**
     * order
     *
     * @param  mixed $order
     * @return void
     */
    public function order(string $order)
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    /**
     * limit
     *
     * @param  mixed $limit
     * @return void
     */
    public function limit(string $limit)
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * offset
     *
     * @param  mixed $offset
     * @return void
     */
    public function offset(string $offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * error
     *
     * @return void
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * message
     *
     * @return void
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * data
     *
     * @return void
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * __set
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * __isset
     *
     * @param  mixed $name
     * @return void
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * __get
     *
     * @param  mixed $name
     * @return void
     */
    public function __get($name)
    {
        return $this->data->$name ?? null;
    }

    /**
     * find
     *
     * @param  mixed $terms
     * @param  mixed $params
     * @param  mixed $columns
     * @return void
     */
    public function find(?string $terms = null, $params = null, string $columns = '*')
    {
        if ($terms) {
            $this->query = "SELECT {$columns} FROM " . $this->entity . " WHERE {$terms} ";
            if ($params !== null) {
                if (is_string($params)) {
                    parse_str($params, $this->params);
                } elseif (is_array($params)) {
                    $this->params = $params;
                } else {
                    throw new \InvalidArgumentException('The $params argument must be a string or an array.');
                }
            }
            return $this;
        }

        $this->query = "SELECT {$columns} FROM " . $this->entity;
        return $this;
    }


    /**
     * result
     *
     * @param  mixed $all
     * @return void
     */
    public function result(bool $all = false)
    {
        try {

            $fullQuery = $this->query . ($this->order ?? '') . ($this->limit ?? '') . ($this->offset ?? '');

            $stmt = Connect::getInstance()->prepare($fullQuery);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);
        } catch (\PDOException $ex) {
            $this->error = $ex->getMessage();
            return null;
        }
    }


    /**
     * add
     *
     * @param  mixed $data
     * @return void
     */
    protected function add(array $data)
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

    /**
     * update
     *
     * @param  mixed $data
     * @param  mixed $terms
     * @return void
     */
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

    /**
     * filter
     *
     * @param  mixed $data
     * @return void
     */
    private function filter(array $data)
    {
        $filter = [];

        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }

        return $filter;
    }

    /**
     * store
     *
     * @return void
     */
    protected function store()
    {
        $data = (array) $this->data;

        return $data;
    }

    /**
     * findByID
     *
     * @param  mixed $id
     * @return void
     */
    public function findByID(int $id)
    {
        $find = $this->find("id = :id", ["id" => $id]);
        return $find->result();
    }

    /**
     * findBySlug
     *
     * @param  mixed $slug
     * @return void
     */
    public function findBySlug(string $slug)
    {
        $find = $this->find("slug = :slug", ["slug" => $slug]);
        return $find->result();
    }

    /**
     * delete
     *
     * @param  mixed $terms
     * @return void
     */
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

    /**
     * destroy
     *
     * @return void
     */
    public function beforeDelete()
    {
        if (empty($this->id)) {
            return false;
        }

        $destroy = $this->delete("id = {$this->id}");
        return $destroy;
    }

    /**
     * count
     *
     * @return int
     */
    public function count(): int
    {
        $stmt = Connect::getInstance()->prepare($this->query);
        $stmt->execute($this->params);

        return $stmt->rowCount();
    }

    /**
     * save
     *
     * @return bool
     */
    public function save(): bool
    {
        //CADASTRAR
        if (empty($this->id)) {
            $id = $this->add($this->store());
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

    /**
     * lastId
     *
     * @return int
     */
    private function lastId(): int
    {
        return Connect::getInstance()->query("SELECT MAX(id) as max FROM {$this->entity}")->fetch()->max + 1;
    }

    /**
     * slug
     *
     * @return void
     */
    protected function slug()
    {
        $checkSlug = $this->find("slug =:s AND id != :id", "s={$this->slug}&id={$this->id}");
        if ($checkSlug->count()) {
            $this->slug = "{$this->slug} - {$this->lastId()}";
        }
    }

    public function saveViews(): void
    {
        $this->views += 1;
        $this->last_views = date('Y-m-d H:i:s');
        $this->save();
    }
}
