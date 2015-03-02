<?php

namespace BsbCrossbarPusherStrategyTest;

use BsbCrossbarPusherStrategy\Module;
use BsbCrossbarPusherStrategyTest\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testFeatureConfigProvider()
    {
        $module = new Module();

        $this->assertInstanceOf('Zend\ModuleManager\Feature\ConfigProviderInterface', $module);
        $this->assertNotEmpty($module->getConfig());
    }

    public function testFeatureDependencyIndicator()
    {
        $module = new Module();

        $this->assertInstanceOf('Zend\ModuleManager\Feature\DependencyIndicatorInterface', $module);
        $this->assertContains('SlmQueue', $module->getModuleDependencies());
    }
}
