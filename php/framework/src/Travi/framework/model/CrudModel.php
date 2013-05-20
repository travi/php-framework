<?php

namespace Travi\framework\model;

use BlogMapper;
use PDO;
use Travi\framework\mappers\CrudMapper;

abstract class CrudModel
{
    /** @var  CrudMapper */
    protected $mapper;
    /** @var PDO */
    protected $db;

    /**
     * @param $db PDO
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param $mapper BlogMapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }
}
