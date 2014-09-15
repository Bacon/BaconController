<?php
namespace BaconController\Mvc\Controller;

use Ajasta\Core\FactoryUtils;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractConsoleControllerBridgeFactory implements AbstractFactoryInterface
{
    /**
     * @var ConsoleControllerManager
     */
    protected $consoleControllerManager;

    /**
     * @var AdapterInterface
     */
    protected $console;

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getConsoleControllerManager($serviceLocator)->has($requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return new ConsoleControllerBridge(
            $this->getConsoleControllerManager($serviceLocator)->get($requestedName),
            $this->getConsole($serviceLocator)
        );
    }

    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ConsoleControllerMAnager
     */
    protected function getConsoleControllerManager(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->consoleControllerManager === null) {
            $this->consoleControllerManager = FactoryUtils::resolveServiceLocator(
                $serviceLocator
            )->get('Ajasta\Core\Mvc\Controller\ConsoleControllerManager');
        }

        return $this->consoleControllerManager;
    }

    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return AdapterInterface
     */
    protected function getConsole(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->console === null) {
            $this->console = FactoryUtils::resolveServiceLocator(
                $serviceLocator
            )->get('Console');
        }

        return $this->console;
    }
}
