<?php

return [
    'bsb_crossbarpusherstrategy' => [
        'topic'           => 'slm.queue.worker.event',
        'verbose'         => false,
    ],
    'slm_queue'                  => [
        'strategy_manager' => [
            'factories' => [
                'BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy'
                => 'BsbCrossbarPusherStrategy\Strategy\Factory\CrossbarPusherStrategyFactory',
            ],
        ],
    ]
];
