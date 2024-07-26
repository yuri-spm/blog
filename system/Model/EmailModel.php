<?php

namespace system\Model;

use PDOException;
use system\Core\Model;


class EmailModel extends Model
{
     /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('mail');
    }
}