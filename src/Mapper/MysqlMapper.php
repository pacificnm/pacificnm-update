<?php
namespace Pacificnm\Update\Mapper;

use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Pacificnm\Update\Entity\Entity;
use Pacificnm\Mapper\AbstractMysqlMapper;

class MysqlMapper extends AbstractMysqlMapper implements MysqlMapperInterface
{
    /**
     *
     * @param AdapterInterface $readAdapter
     * @param AdapterInterface $writeAdapter
     * @param HydratorInterface $hydrator
     * @param Entity $prototype
     */
    public function __construct(AdapterInterface $readAdapter, AdapterInterface $writeAdapter, HydratorInterface $hydrator, Entity $prototype)
    {
        $this->hydrator = $hydrator;
    
        $this->prototype = $prototype;
    
        parent::__construct($readAdapter, $writeAdapter);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Pacificnm\Update\Mapper\MysqlMapperInterface::getAll()
     */
    public function getAll($filter)
    {
        $this->select = $this->readSql->select('update');
        
        $this->filter($filter);
        
        $this->joinModule();
        
        // if pagination is disabled
        if (array_key_exists('pagination', $filter)) {
            if ($filter['pagination'] == 'off') {
                return $this->getRows();
            }
        }
        
        return $this->getPaginator();
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pacificnm\Update\Mapper\MysqlMapperInterface::get()
     */
    public function get($id)
    {
        $this->select = $this->readSql->select('update');
        
        $this->joinModule();
        
        $this->select->where(array(
            'update.update_id = ?' => $id
        ));
        
        return $this->getRow();
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pacificnm\Update\Mapper\MysqlMapperInterface::save()
     */
    public function save(Entity $entity)
    {
        $postData = $this->hydrator->extract($entity);
        
        // if we have id then its an update
        if ($entity->getUpdateId()) {
            $this->update = new Update('update');
        
            $this->update->set($postData);
        
            $this->update->where(array(
                'update.update_id = ?' => $entity->getUpdateId()
            ));
        
            $this->updateRow();
        } else {
            $this->insert = new Insert('update');
        
            $this->insert->values($postData);
        
            $id = $this->createRow();
        
            $entity->setUpdateId($id);
        }
        
        return $this->get($entity->getUpdateId());
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Pacificnm\Update\Mapper\MysqlMapperInterface::delete()
     */
    public function delete(Entity $entity)
    {
        $this->delete = new Delete('update');
        
        $this->delete->where(array(
            'update.update_id = ?' => $entity->getUpdateId()
        ));
        
        return $this->deleteRow();
    }
    
    /**
     * 
     * @param array $filter
     * @return \Pacificnm\Update\Mapper\MysqlMapper
     */
    protected function filter($filter) 
    {
        return $this;
    }
    
    /**
     * 
     * @return \Pacificnm\Update\Mapper\MysqlMapper
     */
    protected function joinModule()
    {
        $this->select->join('module', 'update.module_id = module.module_id', array(
            'module_name',
        ), 'inner');
        
        return $this;
    }
}

