<?php

namespace BsbCrossbarPusherStrategyTest\Strategy;

use BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy;
use PHPUnit_Framework_TestCase as TestCase;
use SlmQueue\Worker\WorkerEvent;

class CrossbarPusherStrategyTest extends TestCase
{
    /**
     * @var CrossbarPusherStrategy
     */
    protected $listener;

    /**
     * @var WorkerEvent
     */
    protected $event;

    public function setUp()
    {
        $queue  = $this->getMockBuilder('SlmQueue\Queue\AbstractQueue')
            ->disableOriginalConstructor()
            ->getMock();
        $worker = $this->getMock('SlmQueue\Worker\WorkerInterface');

        $ev = new WorkerEvent($worker, $queue);

        $this->listener = new CrossbarPusherStrategy();
        $this->event    = $ev;
    }

    public function testListenerInstanceOfAbstractStrategy()
    {
        $this->assertInstanceOf('SlmQueue\Strategy\AbstractStrategy', $this->listener);
    }

    public function testDefaultVerbose()
    {
        $this->assertFalse($this->listener->isVerbose());
    }

    public function testDefaultKey()
    {
        $this->assertNull($this->listener->getKey());
    }

    public function testDefaultSecret()
    {
        $this->assertNull($this->listener->getSecret());
    }

    public function testDefaultEndpoint()
    {
        $this->assertNull($this->listener->getEndpoint());
    }

    public function testDefaultTopic()
    {
        $this->assertEquals('slm.queue.worker.event', $this->listener->getTopic());
    }

    public function testDefaultAdapterOptions()
    {
        $this->assertNull($this->listener->getAdapterOptions());
    }

    public function testListensToCorrectEvents()
    {
        $evm = $this->getMock('Zend\EventManager\EventManagerInterface');

        $evm->expects($this->at(0))->method('attach')
            ->with(WorkerEvent::EVENT_BOOTSTRAP, [$this->listener, 'onWorkerStart']);
        $evm->expects($this->at(1))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_IDLE, [$this->listener, 'onWorkerIdle'], 1000);
        $evm->expects($this->at(2))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_JOB, [$this->listener, 'onJobStart'], 1000);
        $evm->expects($this->at(3))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_JOB, [$this->listener, 'onJobFinish'], -1000);
        $evm->expects($this->at(4))->method('attach')
            ->with(WorkerEvent::EVENT_FINISH, [$this->listener, 'onWorkerFinish']);

        $this->listener->attach($evm);
    }

}
