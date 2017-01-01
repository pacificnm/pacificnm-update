<?php
namespace Pacificnm\Update\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Pacificnm\Update\Service\Service;

class ServiceFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Pacificnm\Update\Service\Service
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $mapper = $serviceLocator->get('Pacificnm\Update\Mapper\MysqlMapperInterface');
        
        return new Service($mapper);
    }
}

