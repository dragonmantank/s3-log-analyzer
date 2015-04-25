<?php
namespace Statistics\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        return new LogTable(
            $services->get('Zend\Db\Adapter\Adapter')
        );
    }
}
