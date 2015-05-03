<?php
namespace Statistics\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Statistics\Controller\StatisticsController;

class StatisticsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllers)
    {
        $services = $controllers->getServiceLocator();
        return new StatisticsController($services->get('Statistics/Model/LogTable'));
    }
}
