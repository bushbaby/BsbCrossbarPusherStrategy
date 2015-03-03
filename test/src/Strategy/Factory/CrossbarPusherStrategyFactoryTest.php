<?php

namespace BsbCrossbarPusherStrategyTest\Strategy\Factory;

use BsbCrossbarPusherStrategy\Strategy\Factory\CrossbarPusherStrategyFactory;
use BsbCrossbarPusherStrategyTest\Bootstrap;
use PHPUnit_Framework_TestCase as TestCase;

class CrossbarPusherStrategyFactoryTest extends TestCase
{

    public function testCreateService()
    {
        $smPluginMock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $smMock       = $this->getMock('Zend\ServiceManager\ServiceManager');
        $smMock->expects($this->once())->method('get')->with('config')->willReturn(Bootstrap::getServiceManager()->get('config'));
        $smPluginMock->expects($this->once())->method('getServiceLocator')->willReturn($smMock);

        $factory = new CrossbarPusherStrategyFactory();
        $service = $factory->createService($smPluginMock);

        $this->assertInstanceOf('BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy', $service);
    }

    public function testCreateServiceWithConstructorOptions()
    {
        $smPluginMock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $smMock       = $this->getMock('Zend\ServiceManager\ServiceManager');
        $smMock->expects($this->once())->method('get')->with('config')->willReturn(Bootstrap::getServiceManager()->get('config'));
        $smPluginMock->expects($this->once())->method('getServiceLocator')->willReturn($smMock);

        $factory = new CrossbarPusherStrategyFactory(['verbose' => true]);
        $service = $factory->createService($smPluginMock);

        $this->assertEquals(true, $service->isVerbose());
    }


}
