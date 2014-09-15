<?php
namespace BaconControllerTest\Mvc\Controller;

use BaconController\Mvc\Controller\ConsoleControllerManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * @coversDefaultClass BaconController\Mvc\Controller\ConsoleontrollerManagerFactory
 * @covers ::<!public>
 */
class ConsoleControllerManagerFactoryTest extends TestCase
{
    /**
     * @covers ::createService
     */
    public function testCreateServiceWithoutConfig()
    {
        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Config', []);

        $factory = new ConsoleControllerManagerFactory();
        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('BaconController\Mvc\Controller\ConsoleControllerManager', $service);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithConfig()
    {
        $controller = $this->getMock('BaconController\Mvc\Controller\ConsoleControllerInterface');

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Config', [
            'console_controllers' => [
                'services' => [
                    'foo' => $controller,
                ],
            ],
        ]);

        $factory = new ConsoleControllerManagerFactory();
        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('BaconController\Mvc\Controller\ConsoleControllerManager', $service);
        $this->assertSame($controller, $service->get('foo'));
    }
}
