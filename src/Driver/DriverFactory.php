<?php

declare(strict_types=1);

namespace j\AintQueue\Client\Driver;

use Closure;
use j\AintQueue\Client\Exception\InvalidDriverException;
use j\AintQueue\Client\QueueInterface;
use function call_user_func;
use function is_string;
use function method_exists;

class DriverFactory
{
    /**
     * Make a instance of QueueInterface.
     *
     * @param string $channel
     * @param array  $options
     *
     * @return QueueInterface
     *
     * @throws InvalidDriverException
     */
    public static function make(string $channel, array $options = []): QueueInterface
    {
        $driverClass = $options['class'] ?? '';
        if (!class_exists($driverClass)) {
            throw new InvalidDriverException(sprintf('[Error] class %s is not found.', $driverClass));
        }

        $driver = new $driverClass($channel, $options);

        if (!$driver instanceof QueueInterface) {
            throw new InvalidDriverException(sprintf('[Error] class %s is not instanceof %s.', $driverClass, QueueInterface::class));
        }

        $encoder = $options['encoder'] ?? null;
        if ($encoder && method_exists($driver, 'setMessageEncoder')) {
            if ($encoder instanceof Closure) {
                $encoder = call_user_func($encoder);
            } elseif (is_string($encoder)) {
                $encoder = new $encoder();
            }
            $driver->setMessageEncoder($encoder);
        }

        return $driver;
    }
}
