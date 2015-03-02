<?php

return [
    'slm_queue' => array(
        'strategy_manager' => array(
            'factories' => array(
                'BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy'
                => 'BsbCrossbarPusherStrategy\Strategy\Factory\CrossbarPusherStrategyFactory',
            ),
        ),
    )
];
