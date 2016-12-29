<?php
namespace Update\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Update\Service\Service;

class ServiceFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Update\Service\Service
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $mapper = $serviceLocator->get('Update\Mapper\MysqlMapperInterface');
        
        return new Service($mapper);
    }
}

