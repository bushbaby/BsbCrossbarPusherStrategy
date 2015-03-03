<?php

namespace BsbCrossbarPusherStrategy\Strategy;

use SlmQueue\Strategy\AbstractStrategy;
use SlmQueue\Worker\WorkerEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\FilterChain;
use Zend\Http\Client;
use Zend\Http\Exception\ExceptionInterface;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Uri\Http;

class CrossbarPusherStrategy extends AbstractStrategy
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $seq = 0;

    /**
     * @var string WAMP topic
     */
    private $topic = 'slm.queue.worker.event';

    /**
     * @var string crossbar.io pusher app key
     */
    private $key;

    /**
     * @var string crossbar.io pusher secret
     */
    private $secret;

    /**
     * @var string crossbar.io pusher endpoint
     */
    private $endpoint;

    /**
     * @var FilterChain a chain of filters to convert 'onMethodName' function names to 'method-name' event names
     */
    private $filter;

    /**
     * @var array defines the adapter used for transport and its options
     */
    private $adapterOptions = null;

    /**
     * @var bool Whether or not to spit out every message to the log
     */
    private $verbose = false;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->filter = new FilterChain();
        $this->filter
            ->attachByName('callback', [
                'callback' => function ($value) {
                    return substr($value, 2);
                }
            ])
            ->attachByName('wordcamelcasetodash')
            ->attachByName('worddashtoseparator', ['separator' => '.'])
            ->attachByName('stringtolower');
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(new Http($this->endpoint), $this->adapterOptions);
        }

        return $this->client;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(WorkerEvent::EVENT_BOOTSTRAP, [$this, 'onWorkerStart']);
        $this->listeners[] = $events->attach(WorkerEvent::EVENT_PROCESS_IDLE, [$this, 'onWorkerIdle'], 1000);
        $this->listeners[] = $events->attach(WorkerEvent::EVENT_PROCESS_JOB, [$this, 'onJobStart'], 1000);
        $this->listeners[] = $events->attach(WorkerEvent::EVENT_PROCESS_JOB, [$this, 'onJobFinish'], -1000);
        $this->listeners[] = $events->attach(WorkerEvent::EVENT_FINISH, [$this, 'onWorkerFinish']);
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = (string) $topic;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = (string) $secret;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = (string) $endpoint;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param boolean $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = (bool) $verbose;
    }

    /**
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * @return array
     */
    public function getAdapterOptions()
    {
        return $this->adapterOptions;
    }

    /**
     * @param array $adapterOptions
     */
    public function setAdapterOptions(array $adapterOptions)
    {
        $this->adapterOptions = $adapterOptions;
    }

    /**
     * @param WorkerEvent $event
     * @return void
     */
    public function onJobStart(WorkerEvent $event)
    {
        $name    = $this->filter->filter(__FUNCTION__);
        $payload = $this->buildPayload($name, $event);
        $params  = $this->getQueryParameters($payload);

        if (($id = $this->push($payload, $params)) && $this->verbose) {
            printf("%s: [pushed %s as %s: id %s]%s", __CLASS__, $event->getName(), $name, $id, PHP_EOL);
        }
    }

    /**
     * @param WorkerEvent $event
     * @return void
     */
    public function onJobFinish(WorkerEvent $event)
    {
        $name    = $this->filter->filter(__FUNCTION__);
        $payload = $this->buildPayload($name, $event);
        $params  = $this->getQueryParameters($payload);

        if (($id = $this->push($payload, $params)) && $this->verbose) {
            printf("%s: [pushed %s as %s: id %s]%s", __CLASS__, $event->getName(), $name, $id, PHP_EOL);
        }
    }

    /**
     * @param WorkerEvent $event
     * @return void
     */
    public function onWorkerStart(WorkerEvent $event)
    {
        $name    = $this->filter->filter(__FUNCTION__);
        $payload = $this->buildPayload($name, $event);
        $params  = $this->getQueryParameters($payload);

        if (($id = $this->push($payload, $params)) && $this->verbose) {
            printf("%s: [pushed %s as %s: id %s]%s", __CLASS__, $event->getName(), $name, $id, PHP_EOL);
        }
    }

    /**
     * @param WorkerEvent $event
     * @return void
     */
    public function onWorkerFinish(WorkerEvent $event)
    {
        $name    = $this->filter->filter(__FUNCTION__);
        $payload = $this->buildPayload($name, $event);
        $params  = $this->getQueryParameters($payload);

        if (($id = $this->push($payload, $params)) && $this->verbose) {
            printf("%s: [pushed %s as %s: id %s]%s", __CLASS__, $event->getName(), $name, $id, PHP_EOL);
        }
    }

    /**
     * @param WorkerEvent $event
     * @return void
     */
    public function onWorkerIdle(WorkerEvent $event)
    {
        $name    = $this->filter->filter(__FUNCTION__);
        $payload = $this->buildPayload($name, $event);
        $params  = $this->getQueryParameters($payload);

        if (($id = $this->push($payload, $params)) && $this->verbose) {
            printf("%s: [pushed %s as %s: id %s]%s", __CLASS__, $event->getName(), $name, $id, PHP_EOL);
        }
    }

    /**
     * @param $payload
     * @param $params
     *
     * @return int|false the message id return by the pusher endpoint or false in case of error
     */
    private function push($payload, $params)
    {
        // get a configured client
        $client = $this->getClient();

        // must use POST
        $client->setMethod(Request::METHOD_POST);

        // update query params of client
        $client->getUri()->setQuery($params);

        // update headers
        $client->setHeaders([
            'Content-Type'   => 'application/json',
            'Content-Lenght' => strlen($payload),
            'User-Agent'     => 'BsbCrossbarPusherBot/1.0 (+https://github.com/bushbaby/BsbCrossbarPusherStrategy)',
        ]);

        // set the request body
        $client->setRawBody($payload);

        try {
            // publish
            $response = $client->send();

            // clear some memory
            $client->resetParameters(true);

            if ($response->getStatusCode() != 202) {
                if ($this->verbose) {
                    printf("%s: [error] [%s] %s", __CLASS__, $response->getStatusCode(), $response->getContent());
                }

                return false;
            }
        } catch (ExceptionInterface $e) {
            // clear some memory
            $client->resetParameters(true);

            printf("%s: [exception] %s%s", __CLASS__, $e->getMessage(), PHP_EOL);

            return false;
        }

        return Json::decode($response->getBody())->id;
    }

    /**
     * Build a payload the pusher endpoint expects
     *
     * @param string      $name
     * @param WorkerEvent $event
     * @return array
     */
    private function buildPayload($name, WorkerEvent $event)
    {
        $payloadJob = null;

        if ($job = $event->getJob()) {
            $payloadJob            = [];
            $payloadJob['id']      = $job->getId();
            $payloadJob['content'] = $job->getContent();
            if ($event->getResult() !== null) {
                $payloadJob['result'] = $event->getResult();
            }
        }

        return Json::encode([
            'topic' => $this->topic,
            'args'  => [
                hash('adler32', spl_object_hash($event->getTarget())),
                $event->getQueue()->getName(),
                $name,
                $payloadJob
            ]
        ]);
    }

    /**
     * Creates an array to be used as query parameters intended to be send to the pusher endpoint
     *
     * @param $payload
     * @return array
     */
    private function getQueryParameters($payload)
    {
        $this->seq++;

        $time = microtime(true);
        $time = $time - floor($time);
        $time = floor($time * 1000);
        $time = str_pad($time, 3, '0', STR_PAD_LEFT);

        $params = [
            'timestamp' => (new \DateTime('now', new \DateTimeZone('utc')))->format('Y-m-d\TH:i:s.' . $time . '\Z'),
            'seq'       => $this->seq,
        ];

        if ($this->key && $this->secret) {
            # HMAC[SHA256]_{secret} (key | timestamp | seq | nonce | body) => signature
            $params['key']       = $this->key;
            $params['nonce']     = rand(0, pow(2, 53));
            $params['signature'] = $this->key . $params['timestamp'] . $this->seq . $params['nonce'] . $payload;
            $params['signature'] = hash_hmac("sha256", $params['signature'], $this->secret, true);
            // base64 url save encoding
            $params['signature'] = strtr(base64_encode($params['signature']), '+/', '-_');
        }

        return $params;
    }
}
