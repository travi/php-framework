<?php

namespace travi\framework\model;

use BlogMapper;
use PDO;
use travi\framework\mappers\CrudMapper;

abstract class CrudModel
{
    /** @var  CrudMapper */
    protected $mapper;
    /** @var PDO */
    protected $db;

    abstract function add($entity);
    abstract function getById($id);
    abstract function updateById($id, $entity);
    abstract function getList($filters = array());
    abstract function deleteById($id);

    /**
     * @param $db PDO
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param $mapper CrudMapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }
}
