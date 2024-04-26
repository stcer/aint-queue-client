<?php

/** @var ClassLoader $loader */

use Composer\Autoload\ClassLoader;
use j\AintQueue\Client\AliasMessageEncoder;
use j\AintQueue\Client\Driver\DriverFactory;
use j\AintQueue\Client\Driver\Redis\Queue as RedisQueue;

$loader = require __DIR__ . '/../vendor/autoload.php';

$channel = 'example';
$driverOption = [
    'class' => RedisQueue::class,
    'connection' => [
        'host' => 'redis.jz.cn',
        'port' => 6379,
        'database' => '0',
        // 'password' => 'password',
    ],
    'encoder' => AliasMessageEncoder::class,
];

/** @var RedisQueue $queue */
$queue = DriverFactory::make($channel, $driverOption);
$queue->push('simple', 2);
$queue->push([
    'simple',
    [
        ['info_id' => 10]
    ]
], 5);

echo "Client send ok\n";
