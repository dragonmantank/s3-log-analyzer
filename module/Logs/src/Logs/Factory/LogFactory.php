<?php
namespace Logs\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Logs\Controller\LogsController;

class LogFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllers)
    {
        $services = $controllers->getServiceLocator();
        return new LogsController($services->get('Logs/Model/LogTable'));
    }
}
