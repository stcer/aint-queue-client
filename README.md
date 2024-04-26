# Aint Queue Client

[Aint Queue](https://github.com/stcer/aint-queue) 是基于 Swoole 的一个异步队列库, 此项目是一个客户端, 用于向 Aint Queue服务器增加延时任务

## Install 

```shell
composer require stcer/aint-queue-client -vvv
```

## Example

客户端增加延时任务示例， 参考[example/demo-client.php](example/demo-client.php)
```php
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
```

服务端配置示例

```php
use Littlesqx\AintQueue\Driver\Redis\Queue as RedisQueue;
use Littlesqx\AintQueue\Serializer\AliasMessageEncoder;
use Stcer\AintQueueDemo\job\SimpleJob;

return [
    'example' => [
        'driver' => [
            'class' => RedisQueue::class,
            'connection' => [
                'host' => 'redis.jz.cn',
                'port' => 6379,
                'database' => '0',
                // 'password' => 'password',
            ],
            'encoder' => function() {
                $encoder = new AliasMessageEncoder();
                $encoder->addClassMap('NTY', SimpleJob::class);
                $encoder->addClassMap('simple', SimpleJob::class);
                return $encoder;
            },
        ],
        'logger' => [
            'class' => \Littlesqx\AintQueue\Logger\DefaultLogger::class,
            'options' => [
                'level' => \Monolog\Logger::DEBUG,
            ],
        ],
        'pid_path' => '/var/run/aint-queue',
        'consumer' => [
            'sleep_seconds' => 1,
            'memory_limit' => 96,
            'dynamic_mode' => true,
            'capacity' => 20,
            'flex_interval' => 5 * 60,
            'min_worker_number' => 5,
            'max_worker_number' => 30,
        ],
        'job_snapshot' => [
            'interval' => 5 * 60,
            'handler' => [],
        ],
    ],
];
```
