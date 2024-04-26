<?php

declare(strict_types=1);

namespace j\AintQueue\Client\Driver\Redis;

use j\AintQueue\Client\AbstractQueue;
use j\AintQueue\Client\Connection\RedisConnector;
use Predis\Client;

class Queue extends AbstractQueue
{
    /**
     * @var RedisConnector|Client
     */
    private $connector;

    /**
     * Queue constructor.
     *
     * @param string $channel
     * @param array  $options
     */
    public function __construct(string $channel, array $options = [])
    {
        parent::__construct($channel, $options);
        $this->initConnection();
    }

    /**
     * Reset redis connection.
     */
    public function initConnection(): void
    {
        $this->connector = RedisConnector::create($this->options['connection'] ?? []);
    }

    /**
     * Disconnect the connection.
     */
    public function destroyConnection(): void
    {
        $this->connector->disconnect();
    }

    /**
     * Get a connection.
     *
     * @return Client|RedisConnector
     */
    public function getConnection()
    {
        return $this->connector;
    }

    /**
     * Push an executable job message into queue.
     *
     * @param string|array $message
     * @param int   $delay
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function push($message, int $delay = 0): void
    {
        $redis = $this->getConnection();

        $id = $redis->incr($this->key("message_id"));
        $redis->hset(
            $this->key("messages"),
            $id,
            $this->getMessageEncoder()->encode($message)
        );

        if ($delay > 0) {
            $redis->zadd($this->key('delayed'), [$id => time() + $delay]);
        } else {
            $redis->lpush($this->key('waiting'), [$id]);
        }
    }

    private function key($name): string
    {
        return "{$this->channelPrefix}{$this->channel}:{$name}";
    }
}
