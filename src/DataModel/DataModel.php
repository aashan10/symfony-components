<?php

namespace Aashan\Workflow\DataModel;


use BadMethodCallException;
use JetBrains\PhpStorm\Pure;

class DataModel
{
    public function __construct(protected array $data = [])
    {
    }

    public function getData(string $key = null): mixed
    {
        if (!$key) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
    }

    public function setData(string $key, string $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function __call(string $name, array $arguments = []): mixed
    {
        $isGetter = strpos($name, 'get');
        $isSetter = strpos($name, 'set');

        if (!$isGetter && !$isSetter) {
            throw new BadMethodCallException();
        }
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        if ($isGetter) {
            return $this->getData($name);
        }

        if ($isSetter) {
            return $this->setData($name, $arguments[0]);
        }
        return null;
    }

    #[Pure]
    public static function create(array $data): self
    {
        return new static($data);
    }
}