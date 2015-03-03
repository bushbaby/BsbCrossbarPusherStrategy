<?php

namespace BsbCrossbarPusherStrategy\Strategy\Factory;

use BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

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
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config         = $serviceLocator->get('config');
        $config         = isset($config['bsb_crossbarpusherstrategy']) ? $config['bsb_crossbarpusherstrategy'] : [];
        $options        = ArrayUtils::merge($config, $this->options);

        return new CrossbarPusherStrategy($options);
    }
}
