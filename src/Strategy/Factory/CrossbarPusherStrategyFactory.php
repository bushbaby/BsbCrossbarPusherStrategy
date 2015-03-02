<?php

namespace BsbCrossbarPusherStrategy\Strategy\Factory;

use BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CrossbarPusherStrategyFactory
 */
class CrossbarPusherStrategyFactory implements FactoryInterface
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CrossbarPusherStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CrossbarPusherStrategy($this->options);
    }
}
