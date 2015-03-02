BsbCrossbarPusherStrategy
=================================

BsbCrossbarPusherStrategy is a strategy for the SlmQueue ZF2 module that provides the ability to publish worker 
events to a crossbar.io [HTTP Pusher Service](http://crossbar.io/docs/HTTP-Pusher-Service/).

***-- NOT PRODUCTION READY --***

[![Latest Stable Version](https://poser.pugx.org/bushbaby/slmqueue-crossbarpusherstrategy/v/stable.svg)](https://packagist.org/packages/bushbaby/slmqueue-crossbarpusherstrategy)
[![Total Downloads](https://poser.pugx.org/bushbaby/slmqueue-crossbarpusherstrategy/downloads.svg)](https://packagist.org/packages/bushbaby/slmqueue-crossbarpusherstrategy)
[![Latest Unstable Version](https://poser.pugx.org/bushbaby/slmqueue-crossbarpusherstrategy/v/unstable.svg)](https://packagist.org/packages/bushbaby/slmqueue-crossbarpusherstrategy)
[![License](https://poser.pugx.org/bushbaby/slmqueue-crossbarpusherstrategy/license.svg)](https://packagist.org/packages/bushbaby/slmqueue-crossbarpusherstrategy)

[![Build Status](https://travis-ci.org/bushbaby/BsbCrossbarPusherStrategy.svg?branch=master)](https://travis-ci.org/bushbaby/BsbCrossbarPusherStrategy)
[![Code Coverage](https://scrutinizer-ci.com/g/bushbaby/BsbCrossbarPusherStrategy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bushbaby/BsbCrossbarPusherStrategy/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bushbaby/BsbCrossbarPusherStrategy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bushbaby/BsbCrossbarPusherStrategy/?branch=master)

- - - - 

## Installation

BsbCrossbarPusherStrategy requires Composer. To install it into your project, just add the following line into your composer.json file:

```
composer.phar require "bushbaby/slmqueue-crossbarpusherstrategy:~1.0"
```

Enable the module by adding BsbCrossbarPusherStrategy in your application.config.php file. 

## Configuration

By enabling this module a new strategy is registered into the strategy manager of SlmQueue. You should then activate it 
by adding it some configuration to the appropriate worker queues. A suggested place is the slm_queue.global.php in your 
autoload configuration directory.

example: enabled the BsbCrossbarPusherStrategy for the queue called default with [finish]

```
'worker_strategies' => array(
    'default' => array( // per worker
    ),
    'queues' => array( // per queue
        'default' => array(
            'BsbCrossbarPusherStrategy\Strategy\CrossbarPusherStrategy' => array(
                ... options
            ),
        ),
    ),
),
```

### CrossbarPusherStrategy options

[to be written]

