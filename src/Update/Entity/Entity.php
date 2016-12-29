<?php
namespace Update\Entity;

use Module\Entity\Entity as ModuleEntity;

class Entity
{

    /**
     *
     * @var number
     */
    protected $updateId;

    /**
     *
     * @var number
     */
    protected $updateDateCheck;

    /**
     *
     * @var number
     */
    protected $moduleId;

    /**
     *
     * @var string
     */
    protected $moduleVersion;

    /**
     *
     * @var string
     */
    protected $updateStatus;

    /**
     *
     * @var ModuleEntity
     */
    protected $moduleEntity;

    /**
     *
     * @return the $updateId
     */
    public function getUpdateId()
    {
        return $this->updateId;
    }

    /**
     *
     * @return the $updateDateCheck
     */
    public function getUpdateDateCheck()
    {
        return $this->updateDateCheck;
    }

    /**
     *
     * @return the $moduleId
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     *
     * @return the $moduleVersion
     */
    public function getModuleVersion()
    {
        return $this->moduleVersion;
    }

    /**
     *
     * @return the $updateStatus
     */
    public function getUpdateStatus()
    {
        return $this->updateStatus;
    }

    /**
     *
     * @return the $moduleEntity
     */
    public function getModuleEntity()
    {
        return $this->moduleEntity;
    }

    /**
     *
     * @param number $updateId            
     */
    public function setUpdateId($updateId)
    {
        $this->updateId = $updateId;
    }

    /**
     *
     * @param number $updateDateCheck            
     */
    public function setUpdateDateCheck($updateDateCheck)
    {
        $this->updateDateCheck = $updateDateCheck;
    }

    /**
     *
     * @param number $moduleId            
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     *
     * @param string $moduleVersion            
     */
    public function setModuleVersion($moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }

    /**
     *
     * @param string $updateStatus            
     */
    public function setUpdateStatus($updateStatus)
    {
        $this->updateStatus = $updateStatus;
    }

    /**
     *
     * @param \Module\Entity\Entity $moduleEntity            
     */
    public function setModuleEntity($moduleEntity)
    {
        $this->moduleEntity = $moduleEntity;
    }
}

