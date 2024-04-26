<?php
#AliasMessageEncoder.php created by stcer@jz at 2024/4/24
namespace j\AintQueue\Client;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use function count;
use function is_array;
use function json_decode;

class AliasMessageEncoder implements JobMessageEncoderInterface
{
    protected $classMap = [
    ];

    public function encode($object): string
    {
        if (is_string($object)) {
            $object = [$object];
        }

        if (!is_array($object)) {
            throw new InvalidArgumentException("Argument one need a array");
        }

        return json_encode($object);
    }

    /**
     * @throws ReflectionException
     */
    public function decode(string $payload)
    {
        $object = json_decode($payload, true);
        if (count($object) > 1) {
            [$class, $arguments] = $object;
        } else {
            [$class] = $object;
            $arguments = [];
        }

        if (!is_array($arguments)) {
            $arguments = [];
        }

        $class = $this->classMap[$class] ?? $class;

        $class = new ReflectionClass($class);
        return $class->newInstanceArgs($arguments);
    }

    /**
     * @param \class-string[] $classMap
     */
    public function setClassMap(array $classMap): void
    {
        $this->classMap = $classMap;
    }

    public function addClassMap($key, $value)
    {
        $this->classMap[$key] = $value;
    }
}
