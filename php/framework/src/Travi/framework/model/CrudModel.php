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
     * @param $mapper BlogMapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }
}
