<?php
namespace Pacificnm\Update\Mapper;

use Pacificnm\Update\Entity\Entity;

interface MysqlMapperInterface
{

    /**
     *
     * @param array $filter            
     * @return Paginator
     */
    public function getAll($filter);

    /**
     *
     * @param number $id            
     * @return Entity
     */
    public function get($id);

    /**
     *
     * @param Entity $entity            
     * @return Entity
     */
    public function save(Entity $entity);

    /**
     *
     * @param Entity $entity            
     * @return boolean
     */
    public function delete(Entity $entity);
}

