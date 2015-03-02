<?php

namespace BsbCrossbarPusherStrategyTest\Factory;

use BsbCrossbarPusherStrategy\Strategy\Factory\CrossbarPusherStrategyFactory;
use PHPUnit_Framework_TestCase as TestCase;

class CrossbarPusherStrategyFactoryTest extends TestCase
{

    public function testCreateService()
    {
        $smPluginMock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');

        $factory = new CrossbarPusherStrategyFactory();
        $service = $factory->createService($smPluginMock);

        $this->assertInstanceOf('BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy', $service);
    }

    public function testCreateServiceWithConstructorOptions()
    {
        $smPluginMock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');

        $factory = new CrossbarPusherStrategyFactory(['verbose' => true]);
        $service = $factory->createService($smPluginMock);

        $this->assertEquals(true, $service->isVerbose());
    }

}
